<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\GithubApiRequests\GithubApiRequest;
use App\Http\Requests\V1\GithubApiRequests\GithubIssuesSearchRequest;
use App\Http\Requests\V1\GithubApiRequests\GithubRepositoriesSearchRequest;
use App\Models\Github\GithubIssueModel;
use App\Models\Github\GithubRepositoryModel;
use App\Models\Github\GithubUserModel;
use App\Support\CollectionUtils;
use App\Support\GithubApi;
use Illuminate\Http\Request;

class GithubApiController extends Controller
{
    const DATA_PER_PAGE = 2;

    /**
     * 1. GET api/v1/github/{userName}/{repositoryName}/issues
     *
     * Получаем список issue
     *
     *
     * @param  string $userName
     * @param  string $repositoryName
     * @param  GithubApiRequest $request
     *
     * @return JSON response
     */
    public function getIssues(string $userName, string $repositoryName, GithubApiRequest $request)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('perPage', self::DATA_PER_PAGE);

        if (!$request->get('fromDb')) {
            $githubApi = GithubApi::createGithubApi($userName);
            $repositoryFromApi = $githubApi->getRepository($repositoryName);
            $usersFromApi = $githubApi->findUser($userName);
            if (count($usersFromApi['users']) == 0) {
                return response()->json([
                    "success" => false,
                    "message" => "Не найдено пользователя $userName",
                ], 404);
            }
            if (!$repositoryFromApi) {
                return response()->json([
                    "success" => false,
                    "message" => "Не найдено репозитория $repositoryName для пользователя $userName",
                ], 404);
            }
            $gitHubUserModel = GithubUserModel::firstOrCreate(['username' => $userName]);

            $gitHubRepositoryModel = $gitHubUserModel->getOrCreateRepository(
                CollectionUtils::replaceKeyInItem($repositoryFromApi, 'id', 'github_id')
            );

            $issuesFromGithubApi = $githubApi->getIssuesOfRepository($repositoryName);
            $issues = $gitHubRepositoryModel->getOrCreateIssues(
                CollectionUtils::renameKeysInData($issuesFromGithubApi, 'id', 'github_id')
            );
        } else {
            $issues = GithubUserModel::where('username', $userName)
                ->firstOrFail()
                ->repositories()
                ->where('name', $repositoryName)
                ->firstOrFail()
                ->issues()
                ->get();
        }
        return response()->json([
            "success" => true,
            "data" => [
                "issues" => CollectionUtils::paginateWithoutKey($issues, $perPage, $page)['data'],
            ],
        ]);

    }

    /**
     * 2. GET api/v1/github/{userName}/repositories
     *
     * Получаем список репозиториев
     *
     * @param  string $userName
     * @param  GithubApiRequest $request
     *
     * @return JSON response
     */
    public function getRepositories(string $userName, GithubApiRequest $request)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('perPage', self::DATA_PER_PAGE);

        if (!$request->input('fromDb')) {
            $githubApi = GithubApi::createGithubApi($userName);
            $repositoriesFromApi = $githubApi->getRepositories();
            $usersFromApi = $githubApi->findUser($userName);
            if (count($usersFromApi['users']) == 0) {
                return response()->json([
                    "success" => false,
                    "message" => "Не найдено пользователя $userName",
                ], 404);
            }
            $gitHubUserModel = GithubUserModel::firstOrCreate(['username' => $userName]);

            $repositories = $gitHubUserModel->getOrCreateRepositories(
                CollectionUtils::renameKeysInData($repositoriesFromApi, 'id', 'github_id')
            );
        } else {
            $repositories = GithubUserModel::where('username', $userName)
                ->firstOrFail()
                ->repositories()
                ->get();
        }
        return response()->json([
            "success" => true,
            "data" => [
                "repositories" => CollectionUtils::paginateWithoutKey($repositories, $perPage, $page)['data'],
            ],
        ]);

    }

    /**
     * 3. GET api/v1/github/{userName}/issues/search
     *
     * @param  string $userName
     * @param  GithubIssuesSearchRequest $request
     *
     * @return JSON response
     */
    public function searchInIssues(string $userName, GithubIssuesSearchRequest $request)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('perPage', self::DATA_PER_PAGE);
        $filters = [
            ['title', 'in', $request->input('title')],
            ['state', 'eq', $request->input('state')],
            ['number', 'eq', $request->input('number')],
        ];

        if (!$request->input('fromDb')) {
            $githubApi = GithubApi::createGithubApi($userName);
            $usersFromApi = $githubApi->findUser($userName);
            if (count($usersFromApi['users']) == 0) {
                return response()->json([
                    "success" => false,
                    "message" => "Не найдено пользователя $userName",
                ], 404);
            }
            $issuesFromApi = $githubApi->searchIssues();
            $issuesSearched = CollectionUtils::searchInCollection($issuesFromApi, $filters);
            $issues = GithubIssueModel::getOrCreateIssues(
                CollectionUtils::renameKeysInData($issuesSearched, 'id', 'github_id')
            );
        } else {
            $issuesFromDb = GithubUserModel::where('username', $userName)
                ->firstOrFail()
                ->issues()
                ->get();
            $issues = CollectionUtils::searchInCollection($issuesFromDb, $filters);
        }
        return response()->json([
            "success" => true,
            "data" => [
                "issues" => CollectionUtils::paginateWithoutKey($issues, $perPage, $page)['data'],
            ],
        ]);
    }

    /**
     * 4. GET api/v1/github/{userName}/repositories/search
     *
     * @param  string $userName
     * @param  GithubRepositoriesSearchRequest $request
     *
     * @return JSON response
     */
    public function searchInRepositories(string $userName, GithubRepositoriesSearchRequest $request)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('perPage', self::DATA_PER_PAGE);
        $filters = [
            ['name', 'in', $request->input('title')],
            ['private', 'eq', $request->input('private')],
            ['language', 'in', $request->input('language')],
        ];

        if (!$request->input('fromDb')) {
            $githubApi = GithubApi::createGithubApi($userName);
            $repositoriesFromApi = $githubApi->searchRepositories();
            $usersFromApi = $githubApi->findUser($userName);
            if (count($usersFromApi['users']) == 0) {
                return response()->json([
                    "success" => false,
                    "message" => "Не найдено пользователя $userName",
                ], 404);
            }
            $repositoriesSearched = CollectionUtils::searchInCollection($repositoriesFromApi, $filters);
            $repositories = GithubRepositoryModel::getOrCreateRepositories(
                CollectionUtils::renameKeysInData($repositoriesSearched, 'id', 'github_id')
            );
        } else {
            $repositoriesFromDb = GithubUserModel::where('username', $userName)
                ->firstOrFail()
                ->repositories()
                ->get();
            $repositories = CollectionUtils::searchInCollection($repositoriesFromDb, $filters);
        }
        return response()->json([
            "success" => true,
            "data" => [
                "repositories" => CollectionUtils::paginateWithoutKey($repositories, $perPage, $page)['data'],
            ],
        ]);
    }
}

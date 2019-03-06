<?php

namespace App\Http\Controllers\V1;

use App\GithubIssue;
use App\GithubRepository;
use App\GithubUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\GithubApiRequest;
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
     * @param  mixed $userName
     * @param  mixed $repositoryName
     * @param  mixed $request
     *
     * @return void
     */
    public function getIssues($userName, $repositoryName, GithubApiRequest $request)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('perPage', self::DATA_PER_PAGE);

        if (!$request->get('fromDb')) {
            $githubApi = GithubApi::createGithubApi($userName);

            $gitHubUserModel = GithubUser::firstOrCreate(['username' => $userName]);

            $repositoryFromApi = $githubApi->getRepository(
                $repositoryName,
                ['id', 'name', 'description', 'private', 'language']
            );

            CollectionUtils::replaceKey($repositoryFromApi, 'id', 'github_id');
            $gitHubRepositoryModel = GithubRepository::firstOrCreate(
                array_merge($repositoryFromApi, ['github_user_id' => $gitHubUserModel->id])
            );

            $issuesFromGithubApi = $githubApi->getIssues($repositoryName, ['id', 'title', 'number', 'state']);
            $issues = collect($issuesFromGithubApi)->map(function ($item) use ($gitHubRepositoryModel) {
                CollectionUtils::replaceKey($item, 'id', 'github_id');
                return GithubIssue::firstOrCreate(
                    array_merge($item, ['repository_id' => $gitHubRepositoryModel->id])
                );
            });

        } else {
            $issues = GithubUser::where('username', $userName)
                ->firstOrFail()
                ->repositories()
                ->where('name', $repositoryName)
                ->firstOrFail()
                ->issues()
                ->get();
        }
        ;
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
     * @param  mixed $userName
     * @param  mixed $request
     *
     * @return void
     */
    public function getRepositories($userName, GithubApiRequest $request)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('perPage', self::DATA_PER_PAGE);

        if (!$request->input('fromDb')) {
            $githubApi = GithubApi::createGithubApi($userName);
            $gitHubUserModel = GithubUser::firstOrCreate(['username' => $userName]);
            $repositoriesFromApi = $githubApi->getRepositories(
                ['id', 'name', 'description', 'private', 'language']
            );
            $repositories = collect($repositoriesFromApi)->map(function ($item) use ($gitHubUserModel) {
                CollectionUtils::replaceKey($item, 'id', 'github_id');
                return GithubRepository::firstOrCreate(
                    array_merge($item, ['github_user_id' => $gitHubUserModel->id])
                );
            });
        } else {
            $repositories = GithubUser::where('username', $userName)
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

}

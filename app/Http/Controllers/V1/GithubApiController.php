<?php

namespace App\Http\Controllers\V1;

use App\GithubIssue;
use App\GithubRepository;
use App\GithubUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\GithubApiRequest;
use App\Support\GithubApi\GithubApi;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;


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
        $perPage = $request->input('perPage', GithubApiController::DATA_PER_PAGE);

        if (!$request->get('fromDb')) {

            $githubApi = GithubApi::createGithubApi($userName);

            $gitHubUserModel = GithubUser::firstOrCreate(['username' => $userName]);
            $repositoryFromApi = $githubApi->getRepository($repositoryName);
            $gitHubRepositoryModel = GithubRepository::firstOrCreate(array_merge($repositoryFromApi, [
                'github_user_id' => $gitHubUserModel->id,
            ]));
            $issuesFromApi = $githubApi->getIssues($repositoryName);

            $issues = array();
            foreach ($issuesFromApi as $issue) {
                array_push($issues, GithubIssue::firstOrCreate(array_merge([
                    'repository_id' => $gitHubRepositoryModel->id,
                ], $issue)));
            }
    
            $collection = new Collection($issues);
            $issues = $collection->slice(($page - 1) * $perPage, $perPage)->all();
            
        } else {
            $issues = GithubUser::where('username', $userName)
                ->firstOrFail()
                ->repositories()
                ->where('name', $repositoryName)
                ->firstOrFail()
                ->issues()
                ->paginate($perPage)
                ->except(['data']);
        }

        return response()->json([
            "success" => true,
            "data" => [
                "issues" => $issues
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
        $perPage = $request->input('perPage', GithubApiController::DATA_PER_PAGE);


        if (!$request->input('fromDb')) {
            $githubApi = GithubApi::createGithubApi($userName);
            $gitHubUserModel = GithubUser::firstOrCreate(['username' => $userName]);
            $repositoriesFromApi = $githubApi->getRepositories();
            $repositories = array();
            foreach ($repositoriesFromApi as $repo) {
                array_push($repositories, GithubRepository::firstOrCreate(array_merge([
                    'github_user_id' => $gitHubUserModel->id,
                ], $repo)));
            }
    
            $collection = new Collection($repositories);
            $repositories = $collection->slice(($page - 1) * $perPage, $perPage)->all();
            
        } else {
            $repositories = GithubUser::where('username', $userName)
                ->firstOrFail()
                ->repositories()
                ->paginate($perPage)
                ->except(['data']);
        }

        return response()->json([
            "success" => true,
            "data" => [
                "repositories" => $repositories
            ],
        ]);

    }

}

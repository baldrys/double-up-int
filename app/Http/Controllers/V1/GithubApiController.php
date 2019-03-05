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
        $perPage = $request->input('perPage', self::DATA_PER_PAGE);

        if (!$request->get('fromDb')) {

            $githubApi = GithubApi::createGithubApi($userName);

            $gitHubUserModel = GithubUser::firstOrCreate(['username' => $userName]);

            $repositoryFromApi = $githubApi->getRepository($repositoryName);
            $repositoryWithUserId = array_merge($repositoryFromApi, ['github_user_id' => $gitHubUserModel->id]);
            $gitHubRepositoryModel = GithubRepository::firstOrCreate($repositoryWithUserId);
            
            $issuesFromApi = $githubApi->getIssues($repositoryName);
            $issuesForRepository = self::addItemToSubArrays(['repository_id'=> $gitHubRepositoryModel->id], $issuesFromApi);
            $issues = self::getOrCreateRecords($issuesForRepository, 'App\GithubIssue')->paginate($perPage);

            // $collection = new Collection($issues);
            // $issuesModels = $collection->slice(($page - 1) * $perPage, $perPage)->all();

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
     * Добавляет элемент ко всем подмассивам
     * 
     * Вовзращает новый массив
     *
     * @param  mixed $item
     * @param  Array $array
     *
     * @return Array
     */
    static function addItemToSubArrays($item, $array) {
        $modifiedArray = array();
        foreach($array as $subArray)
        {
            array_push($modifiedArray, array_merge($subArray, $item));
        }
        return $modifiedArray;   
    } 


    /**
     * Возвращает записи в модели, если их нет то создает их
     * 
     *
     * @param  Array $data
     * @param  mixed $model
     *
     * @return Array
     */
    static function getOrCreateRecords(Array $data, $model) {
        // $updatedRecords = array();
        $updatedRecords = new Collection();
            // $issuesModels = $collection->slice(($page - 1) * $perPage, $perPage)->all();
           foreach ($data as $record) {
            // array_push($updatedRecords, $model::firstOrCreate($record));
            $updatedRecords->push($model::firstOrCreate($record));
        }
        return $updatedRecords;   
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

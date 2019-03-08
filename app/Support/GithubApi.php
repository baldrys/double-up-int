<?php

namespace App\Support;

use App\Support\CollectionUtils;
use Github\Client;
use Illuminate\Support\Facades\Schema;

final class GithubApi
{
    const COLUMNS_IN_GITHUB_REPOSITORY_MODEL = ['id', 'name', 'description', 'private', 'language'];
    const COLUMNS_IN_GITHUB_ISSUE_MODEL = ['id', 'title', 'number', 'state'];


    private $__client;
    private $__userName;

    public function __construct($userName)
    {
        $this->client = new Client();
        $this->userName = $userName;
    }

    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * Поиск пользователя по нику
     *
     * @param  mixed $userName
     *
     * @return void
     */
    public function findUser($userName){
        try {
            $user = $this->client->api('user')->find($userName);
        } catch (\Github\Exception\RuntimeException $ex) {
            return null;
        } catch (\Throwable $th) {
            throw $th;
        }
        return $user;
    }   

    /**
     * Получаем список issues с гитхаба для заданных полей
     *
     * @param  string $repositoryName
     *
     * @return array
     */
    public function getIssuesOfRepository($repositoryName)
    {
        try {
            $issues = $this->client->api('issue')->all($this->getUserName(), $repositoryName);
        } catch (\Github\Exception\RuntimeException $ex) {
            return null;
        } catch (\Throwable $th) {
            throw $th;
        }
        return CollectionUtils::parseData($issues, self::COLUMNS_IN_GITHUB_ISSUE_MODEL);
    }


    /**
     * Получаем список repository с гитхаба для заданных полей
     *
     *
     * @return array
     */
    public function getRepositories()
    {
        try {
            $repositories = $this->client->api('user')->repositories($this->getUserName());
        } catch (\Github\Exception\RuntimeException $ex) {
            return null;
        } catch (\Throwable $th) {
            throw $th;
        }
        return CollectionUtils::parseData($repositories, self::COLUMNS_IN_GITHUB_REPOSITORY_MODEL);
    }

    /**
     * Получаем repository с гитхаба для заданных полей
     *
     * @param  string $repositoryName
     *
     * @return array
     */
    public function getRepository($repositoryName)
    {
        try {
            $repo = $this->client->api('repo')->show($this->getUserName(), $repositoryName);
        } catch (\Github\Exception\RuntimeException $ex) {
            return null;
        } catch (\Throwable $th) {
            throw $th;
        }
        return collect($repo)->only(self::COLUMNS_IN_GITHUB_REPOSITORY_MODEL)->all();
    }


    /**
     * 
     *
     * @param  string $repositoryName
     * @param  array $fieldsToSearch
     *
     * @return array
     */
    public function searchIssues(string $stringToSearch = '')
    {
        $user = $this->getUserName();
        try {
            $issues = $this->client->api('search')->issues("user:".$user.$stringToSearch);
        } catch (\Github\Exception\RuntimeException $ex) {
            return null;
        } catch (\Throwable $th) {
            throw $th;
        }
        return CollectionUtils::parseData($issues['items'], self::COLUMNS_IN_GITHUB_ISSUE_MODEL);
    }

    /**
     * 
     *
     * @param  string $repositoryName
     * @param  array $fieldsToSearch
     *
     * @return array
     */
    public function searchRepositories(string $stringToSearch = '')
    {
        $user = $this->getUserName();
        try {
            $repositories = $this->client->api('search')->repositories("user:".$user.$stringToSearch);
        } catch (\Github\Exception\RuntimeException $ex) {
            return null;
        } catch (\Throwable $th) {
            throw $th;
        }
        return CollectionUtils::parseData($repositories['items'], self::COLUMNS_IN_GITHUB_REPOSITORY_MODEL);
    }

    public static function createGithubApi($userName)
    {
        return new GithubApi($userName);
    }

}

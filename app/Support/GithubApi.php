<?php

namespace App\Support;

use App\Support\CollectionUtils;
use Github\Client;

final class GithubApi
{
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
     * Получаем список issues с гитхаба для заданных полей
     *
     * @param  string $repositoryName
     * @param  array $fieldsToSearch
     *
     * @return array
     */
    public function getIssues($repositoryName, $fieldsToSearch = [])
    {
        try {
            $issues = $this->client->api('issue')->all($this->getUserName(), $repositoryName);
        } catch (\Github\Exception\RuntimeException $ex) {
            abort(404, $ex->getMessage());
        } catch (\Throwable $th) {
            throw $th;
        }
        return CollectionUtils::parseData($issues, $fieldsToSearch);
    }


    /**
     * Получаем список repository с гитхаба для заданных полей
     *
     * @param  array $fieldsToSearch
     *
     * @return array
     */
    public function getRepositories($fieldsToSearch = [])
    {
        try {
            $repositories = $this->client->api('user')->repositories($this->getUserName());
        } catch (\Github\Exception\RuntimeException $ex) {
            abort(404, $ex->getMessage());
        } catch (\Throwable $th) {
            throw $th;
        }
        return CollectionUtils::parseData($repositories, $fieldsToSearch);
    }

    /**
     * Получаем repository с гитхаба для заданных полей
     *
     * @param  string $repositoryName
     * @param  array $fieldsToSearch
     *
     * @return array
     */
    public function getRepository($repositoryName, $fieldsToSearch = [])
    {
        try {
            $repo = $this->client->api('repo')->show($this->getUserName(), $repositoryName);
        } catch (\Github\Exception\RuntimeException $ex) {
            abort(404, $ex->getMessage());
        } catch (\Throwable $th) {
            throw $th;
        }
        return collect($repo)->only($fieldsToSearch)->all();
    }

    public static function createGithubApi($userName)
    {
        return new GithubApi($userName);
    }

}

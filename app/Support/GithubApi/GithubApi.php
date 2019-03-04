<?php


namespace App\Support\GithubApi;

use Github\Client;


final class GithubApi
{
    private $client;
    private $userName;

    function __construct($userName) {
        $this->client = new Client();
        $this->userName = $userName;
    }

    function getUserName(){
        return $this->userName;
    }

    # TODO Try catch
    function getIssues($repositoryName) {
        try {
            $issues = $this->client->api('issue')->all($this->getUserName(), $repositoryName);
        } catch (\Github\Exception\RuntimeException $ex) {
            // return null;
            abort(404, $ex->getMessage());
        } catch (\Throwable $th) {
            throw $th;
        }
        $issuesParsed = array();
        foreach ($issues as $issue) {
            $issueParsed = array(
                'github_id' => $issue['id'],
                'title' => $issue['title'],
                'number' => $issue['number'],
                'state' => $issue['state'],
            );
            array_push($issuesParsed, $issueParsed);
        }
        return $issuesParsed;
    }

    # TODO Try catch
    function getRepositories() {
        try {
            $repositories  = $this->client->api('user')->repositories($this->getUserName());
        } catch (\Github\Exception\RuntimeException $ex) {
            abort(404, $ex->getMessage());
        } catch (\Throwable $th) {
            throw $th;
        }
        $repositoriesParsed = array();
        foreach ($repositories as $repository) {
            $repositoryParsed = array(
                'github_id' => $repository['id'],
                'name' => $repository['name'],
                'description' => $repository['description'],
                'private' => $repository['private'],
                'language' => $repository['language'],
            );
            array_push($repositoriesParsed, $repositoryParsed);
        }
        return $repositoriesParsed;
    }

    function getRepository($repositoryName) {
        try {
            $repo = $this->client->api('repo')->show($this->getUserName(), $repositoryName);
        } catch (\Github\Exception\RuntimeException $ex) {
            abort(404, $ex->getMessage());
        } catch (\Throwable $th) {
            throw $th;
        }
        $repositoryParsed = array(
            'github_id' => $repo['id'],
            'name' => $repo['name'],
            'description' => $repo['description'],
            'private' => $repo['private'],
            'language' => $repo['language'],
        );
        return $repositoryParsed;
    }

    static function createGithubApi($userName){
        return new GithubApi($userName);
    }

}
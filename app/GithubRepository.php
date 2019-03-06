<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GithubRepository extends Model
{
    public $timestamps = false;
    protected $table = 'github_repositories';
    protected $fillable = [
        'github_id', 'github_user_id', 'name', 'description', 'private', 'language',
    ];

    public function issues()
    {
        return $this->hasMany('App\GithubIssue', 'repository_id');
    }

    /**
     * Возвращает или создает Issues с указанными данными
     *
     * @param  array $data
     *
     * @return array
     */
    public function getOrCreateIssues($data)
    {
        return collect($data)->map(function ($item) {

            return $this->getOrCreateIssue($item);

        })->all();
    }

    /**
     * Возвращает или создает Issue с указанными данными
     *
     * @param  array $data
     *
     * @return Model GithubIssue
     */
    public function getOrCreateIssue($data)
    {
        return GithubIssue::firstOrCreate(
            array_merge($data, ['repository_id' => $this->id])
        );

    }
}

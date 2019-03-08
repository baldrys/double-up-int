<?php

namespace App\Models\Github;

use Illuminate\Database\Eloquent\Model;

class GithubUserModel extends Model
{
    public $timestamps = false;
    protected $table = 'github_users';
    protected $fillable = ['username'];

    public function repositories()
    {
        return $this->hasMany('App\Models\Github\GithubRepositoryModel', 'github_user_id');
    }

    /**
     * Возвращает или создает repositories с указанными данными
     *
     * @param  array $data
     *
     * @return array
     */
    public function getOrCreateRepositories($data)
    {
        return collect($data)->map(function ($item) {

            return $this->getOrCreateRepository($item);

        })->all();
    }

    /**
     * Возвращает или создает repository с указанными данными
     *
     * @param  array $data
     *
     * @return Model GithubRepositoryModel
     */
    public function getOrCreateRepository($data)
    {
        return GithubRepositoryModel::firstOrCreate(
            array_merge($data, ['github_user_id' => $this->id])
        );
    }

    public function issues()
    {
        return $this->hasManyThrough(
            'App\Models\Github\GithubIssueModel', 
            'App\Models\Github\GithubRepositoryModel',
            'github_user_id',
            'repository_id',
            'id',
            'id'
        );
    }

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GithubUser extends Model
{
    public $timestamps = false;
    protected $_table = 'github_users';
    protected $_fillable = ['username'];

    public function repositories()
    {
        return $this->hasMany('App\GithubRepository');
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
     * @return Model GithubRepository
     */
    public function getOrCreateRepository($data)
    {
        return GithubRepository::firstOrCreate(
            array_merge($data, ['github_user_id' => $this->id])
        );

    }

}

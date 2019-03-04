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

}

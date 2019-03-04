<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GithubUser extends Model
{
    public $timestamps = false;
    protected $table = 'github_users';
    protected $fillable = ['username', ];
    
    public function repositories()
    {
        return $this->hasMany('App\GithubRepository');
    }


}

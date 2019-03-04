<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GithubIssue extends Model
{
    public $timestamps = false;
    protected $table = 'github_issues';
    protected $fillable = [
        'github_id', 'repository_id', 'title', 'number', 'state',
    ];
}

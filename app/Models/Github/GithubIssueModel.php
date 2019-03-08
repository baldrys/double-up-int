<?php

namespace App\Models\Github;

use Illuminate\Database\Eloquent\Model;

class GithubIssueModel extends Model
{
    public $timestamps = false;
    protected $table = 'github_issues';
    protected $fillable = [
        'github_id', 'repository_id', 'title', 'number', 'state',
    ];

    /**
     * Возвращает или создает Issues с указанными данными
     *
     * @param  array $data
     *
     * @return array
     */
    public static function getOrCreateIssues($data)
    {
        return collect($data)->map(function ($item) {

            return GithubIssueModel::firstOrCreate($item);

        })->all();
    }
}

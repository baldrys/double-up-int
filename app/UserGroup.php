<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    public $timestamps = false;
    protected $table = 'user_group';
    protected $hidden = ['pivot'];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

// Промежуточная таблица для связи многие ко многим таблиц users и user_group
class UserGroups extends Model
{
    public $timestamps = false;
    protected $table = 'user_groups';
}

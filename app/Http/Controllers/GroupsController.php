<?php

namespace App\Http\Controllers;

use App\User;
use App\UserGroup;

class GroupsController extends Controller
{
    public function showUserGroups(User $user)
    {
        $groups = $user->groups()->get();
        return view('groups.userGroups')
            ->with('groups', $groups)
            ->with('user', $user);
    }

    public function index(User $user)
    {
        $groups = UserGroup::all();
        return view('groups.index')
            ->with('groups', $groups);
    }
}

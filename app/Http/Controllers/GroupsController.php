<?php

namespace App\Http\Controllers;

use App\User;

class GroupsController extends Controller
{
    public function index(User $user)
    {
        $groups = $user->groups()->get();
        return view('groups.index')
            ->with('groups', $groups)
            ->with('user', $user);
    }
}

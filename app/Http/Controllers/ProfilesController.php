<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserProfile;

class ProfilesController extends Controller
{
    function index(){
        $profiles = UserProfile::all();
        return view('profiles.index')->with('profiles', $profiles);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfilesController extends Controller
{
    function index(){
        return view('profiles.index');
    }
}

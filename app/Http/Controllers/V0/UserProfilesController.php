<?php

namespace App\Http\Controllers\V0;

use App\Http\Controllers\Controller;
use App\User;
use App\UserProfile;
use Illuminate\Http\Request;

class UserProfilesController extends Controller
{

    // 1. роут GET api/v0/users/profile/{id}
    public function show_profile($id)
    {
        $user_profile = UserProfile::find($id);
        if (!$user_profile){
            abort(404, "Профиль пользователя с id = $id не найден");
        }
        return response()->json([
            'success' => true, 
            'data' => [
                'profile' => $user_profile,
                ]
            ]);
    }

    // 2. роут GET api/v0/user/{userId}/profiles
    public function show_profiles($id)
    {   
        $user = User::find($id);
        if(!$user){
            abort(404, "Пользователь с id = $id не найден");
        }
        $user_profiles = UserProfile::where('user_id', '=', $id)->get();
        return response()->json([
            'success' => true, 
            'data' => [
                'profiles' =>$user_profiles,
                ]
            ]);
    }

    // 3. роут GET api/v0/users/profiles
    // возвращает все профили по 5 на страницу, если профилей нет - пустой массив
    // Параметры: 1. page обязательно >= 1
    public function show_profiles_5_per_page(Request $request)
    {
        $page = $request->get('page');
        if ($page < 1) {
            abort(404, "Недопустимый номер страницы $page");
        }
        $user_profiles = UserProfile::paginate(5)->items();
        return response()->json([
            'success' => true, 
            'data' => [
                'profiles' =>$user_profiles,
                ]
            ]);
    }
    
    // 4. роут PATCH api/v0/users/profile/{id}
    // Параметры: 1 name - обновляет имя профиля
    public function update_profile($id, Request $request)
    {
        $name = $request->get('name');
        $user_profile = UserProfile::find($id);
        if (!$user_profile){
            abort(404, "Профиль пользователя с id = $id не найден");
        }
        if (!$name){
            abort(404, "Имя не было передано!");
        }
        $user_profile->name = $name;
        $user_profile->save();
        return response()->json([
            'success' => true, 
            'data' => [
                'profile' => $user_profile,
                ]
            ]);
    }

    // 5. роут DELETE api/v0/users/profile/{id}
    public function delete_profile($id)
    {
        $user_profile = UserProfile::find($id);
        if (!$user_profile){
            abort(404, "Профиль пользователя с id = $id не найден");
        }
        $user->delete();
        return response()->json(['success' => true]);
    }
}
<?php

namespace App\Http\Controllers\V0;

use App\Http\Controllers\Controller;
use App\User;
use App\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Support\Task2Utils;

class UserProfilesController extends Controller
{
    protected const NUMBER_OF_PROFILES = 5;

    /**
     * 
     * 1. роут GET api/v0/users/profile/{id}
     * возвращает профиль пользователя
     * 
     * @param  int $id
     *
     * @return JSON 
     * 
     */
    public function showProfile($id)
    {
        $userProfile = UserProfile::find($id);
        Task2Utils::validateData($userProfile,'!=', null, 404, "Профиль пользователя с id = $id не найден");
        return response()->json(Task2Utils::getSuccessArray($userProfile, 'profile'));
    }
    
    /**
     * 2. роут GET api/v0/user/{userId}/profiles
     * возвращает все профили пользователя
     * 
     * @param  int $id
     *
     * @return JSON
     */
    public function showProfiles($id)
    {   
        $user = User::find($id);
        Task2Utils::validateData($user, '!=', null, 404, "Пользователь с id = $id не найден");
        $userProfiles = UserProfile::where('user_id', $id)->get();      
        return response()->json(Task2Utils::getSuccessArray($userProfiles, 'profiles'));
    }


    /**
     * 3. роут GET api/v0/users/profiles
     * возвращает все профили по 5 на страницу, если профилей нет - пустой массив
     * Параметры: 1. page обязательно >= 1
     *
     * @param  Request $request
     *
     * @return JSON
     */
    public function showProfilesPerPage(Request $request)
    {
        $page = $request->get('page');
        Task2Utils::validateData($page, '>=', 1, 400, "Bad Request");
        $userProfiles = UserProfile::paginate(self::NUMBER_OF_PROFILES)->items();
        return response()->json(Task2Utils::getSuccessArray($userProfiles, 'profiles'));
    }


    /**
     * 4. роут PATCH api/v0/users/profile/{id}
     * Параметры: 1 name - обновляет имя профиля
     *
     * @param  int $id
     * @param  Request $request
     *
     * @return JSON
     */
    public function updateProfile($id, Request $request)
    {
        $name = $request->get('name');
        $userProfile = UserProfile::find($id);
        Task2Utils::validateData($userProfile, '!=', null, 404, "Профиль пользователя с id = $id не найден");
        Task2Utils::validateData($name, '!=', null, 400, "Bad Request");
        $userProfile->name = $name;
        $userProfile->save();
        return response()->json(Task2Utils::getSuccessArray($userProfile, 'profile'));
    }

    /**
     * 5. роут DELETE api/v0/users/profile/{id}
     *
     * @param  int $id
     *
     * @return JSON
     */
    public function deleteProfile($id)
    {
        $userProfile = UserProfile::find($id);
        Task2Utils::validateData($userProfile, '!=', null, 404, "Профиль пользователя с id = $id не найден");
        $user->delete();
        return response()->json(['success' => true]);
    }
    
    /**
     * 
     * 6. роут GET api/v0/db/users/profile/{id}
     * возвращает профиль пользователя
     * 
     * @param  int $id
     *
     * @return JSON 
     * 
     */
    public function showProfileDB($id)
    {
        $userProfile = DB::table('user_profiles')->where('id', $id)->first();
        Task2Utils::validateData($userProfile,'!=', null, 404, "Профиль пользователя с id = $id не найден");
        return response()->json(Task2Utils::getSuccessArray($userProfile, 'profile'));
    }

    /**
     * 7. роут GET api/v0/db/user/{userId}/profiles
     * возвращает все профили пользователя
     * 
     * @param  int $id
     *
     * @return JSON
     */
    public function showProfilesDB($id)
    {   
        $user = DB::table('users')->where('id', $id)->first();
        Task2Utils::validateData($user, '!=', null, 404, "Пользователь с id = $id не найден");
        $userProfiles = DB::table('user_profiles')->where('user_id', $id)->get();   
        return response()->json(Task2Utils::getSuccessArray($userProfiles, 'profiles'));
    }

    /**
     * 8. роут GET api/v0/db/users/profiles
     * возвращает все профили по 5 на страницу, если профилей нет - пустой массив
     * Параметры: 1. page обязательно >= 1
     *
     * @param  Request $request
     *
     * @return JSON
     */
    public function showProfilesPerPageDB(Request $request)
    {
        $page = $request->get('page');
        Task2Utils::validateData($page, '>=', 1, 400, "Bad Request");
        $userProfiles = DB::table('user_profiles')->paginate(self::NUMBER_OF_PROFILES)->items();
        return response()->json(Task2Utils::getSuccessArray($userProfiles, 'profiles'));
    }

    /**
     * 9. роут PATCH api/v0/db/users/profile/{id}
     * Параметры: 1 name - обновляет имя профиля
     *
     * @param  int $id
     * @param  Request $request
     *
     * @return JSON
     */
    public function updateProfileDB($id, Request $request)
    {
        $name = $request->get('name');
        $userProfile = DB::table('user_profiles')->where('id', $id)->first();
        Task2Utils::validateData($userProfile, '!=', null, 404, "Профиль пользователя с id = $id не найден");
        Task2Utils::validateData($name, '!=', null, 400, "Bad Request");
        DB::table('user_profiles')
            ->where('id', $id)
            ->update(['name' => $name]);
        $updatedUserProfile = DB::table('user_profiles')->where('id', $id)->first();
        return response()->json(Task2Utils::getSuccessArray($updatedUserProfile, 'profile'));
    }

    /**
     * 10. роут DELETE api/v0/db/users/profile/{id}
     *
     * @param  int $id
     *
     * @return JSON
     */
    public function deleteProfileDB($id)
    {
        $userProfile = DB::table('user_profiles')->where('id', $id)->first();
        Task2Utils::validateData($userProfile, '!=', null, 404, "Профиль пользователя с id = $id не найден");
        DB::table('user_profiles')->where('id', $id)->delete();
        return response()->json(['success' => true]);
    }
}

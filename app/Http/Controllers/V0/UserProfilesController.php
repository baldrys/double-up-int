<?php

namespace App\Http\Controllers\V0;

use App\Http\Controllers\Controller;
use App\User;
use App\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserProfilesController extends Controller
{
    protected const PROFILES_PER_PAGE = 5;

    /**
     * Получает ассоциативный массив для заданной структуры
     *
     * @param  mixed  $data
     * @param  string $dataName
     *
     * @return Array
     */
    public static function getSuccessArray($data, string $dataName){
        return [
            'success' => true, 
            'data' => [
                $dataName => $data,
                ]
            ];
    }
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
        if(!$userProfile){
            abort(404, "Профиль пользователя с id = $id не найден");
        }
        return response()->json(UserProfilesController::getSuccessArray($userProfile, 'profile'));
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
        $userProfile = UserProfile::find($id);
        if(!$user){
            abort(404, "Пользователь с id = $id не найден");
        }
        $userProfiles = UserProfile::where('user_id', $id)->get();      
        return response()->json(UserProfilesController::getSuccessArray($userProfiles, 'profiles'));
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
        $this->validate($request, ['page'=>'required|integer|min:1']);
        $page = $request->get('page');
        $userProfiles = UserProfile::paginate(self::PROFILES_PER_PAGE)->items();
        return response()->json(UserProfilesController::getSuccessArray($userProfiles, 'profiles'));
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
        $this->validate($request,['name'=>'required']);
        $name = $request->get('name');
        $userProfile = UserProfile::find($id);
        if(!$userProfile){
            abort(404, "Профиль пользователя с id = $id не найден");
        }
        $userProfile->name = $name;
        $userProfile->save();
        return response()->json(UserProfilesController::getSuccessArray($userProfile, 'profile'));
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
        if(!$userProfile){
            abort(404, "Профиль пользователя с id = $id не найден");
        }
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
        if(!$userProfile){
            abort(404, "Профиль пользователя с id = $id не найден");
        }
        return response()->json(UserProfilesController::getSuccessArray($userProfile, 'profile'));
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
        if(!$user){
            abort(404, "Пользователь с id = $id не найден");
        }
        $userProfiles = DB::table('user_profiles')->where('user_id', $id)->get();   
        return response()->json(UserProfilesController::getSuccessArray($userProfiles, 'profiles'));
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
        $this->validate($request,['page'=>'required|integer|min:1']);
        $userProfiles = DB::table('user_profiles')->paginate(self::PROFILES_PER_PAGE)->items();
        return response()->json(UserProfilesController::getSuccessArray($userProfiles, 'profiles'));
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
        $this->validate($request,['name'=>'required']);
        $name = $request->get('name');
        $userProfile = DB::table('user_profiles')->where('id', $id)->first();
        if(!$userProfile){
            abort(404, "Профиль пользователя с id = $id не найден");
        }
        DB::table('user_profiles')
            ->where('id', $id)
            ->update(['name' => $name]);
        $updatedUserProfile = DB::table('user_profiles')->where('id', $id)->first();
        return response()->json(UserProfilesController::getSuccessArray($updatedUserProfile, 'profile'));
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
        if(!$userProfile){
            abort(404, "Профиль пользователя с id = $id не найден");
        }
        DB::table('user_profiles')->where('id', $id)->delete();
        return response()->json(['success' => true]);
    }
}

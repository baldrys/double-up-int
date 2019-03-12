<?php

namespace App\Http\Controllers\V0;

use App\Http\Controllers\Controller;
use App\Http\Requests\NameRequest;
use App\Http\Requests\PageRequest;
use App\Http\Resources\UserProfile as UserProfileResource;
use App\Http\Resources\UserProfileCollection;
use App\Jobs\SendEmailJob;
use App\Mail\ProfileUpdated;
use App\User;
use App\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;

class UserProfilesController extends Controller
{
    protected const PROFILES_PER_PAGE = 5;

    /**
     *
     * 1. роут GET api/v0/users/profile/{userProfile}
     * возвращает профиль пользователя
     *
     * @param  UserProfile $userProfile
     *
     * @return JSON
     *
     */
    public function showProfile(UserProfile $userProfile)
    {
        return new UserProfileResource($userProfile);
    }

    /**
     * 2. роут GET api/v0/user/{user}/profiles
     * возвращает все профили пользователя
     *
     * @param  User $user
     *
     * @return JSON
     */
    public function showProfiles(User $user)
    {
        $userProfiles = UserProfile::where('user_id', $user->id)->get();
        return new UserProfileCollection($userProfiles);
    }

    /**
     * 3. роут GET api/v0/users/profiles
     * возвращает все профили по 5 на страницу, если профилей нет - пустой массив
     * Параметры: 1. page обязательно >= 1
     *
     * @param  PageRequest $request
     *
     * @return JSON
     */
    public function showProfilesPerPage(PageRequest $request)
    {
        $userProfiles = UserProfile::paginate(self::PROFILES_PER_PAGE)->except(['data']);
        return new UserProfileCollection($userProfiles);
    }

    /**
     * 4. роут PATCH api/v0/users/profile/{userProfile}
     * Параметры: 1 name - обновляет имя профиля
     *
     * @param  NameRequest $userProfile
     * @param  Request $request
     *
     * @return JSON
     */
    public function updateProfile(UserProfile $userProfile, NameRequest $request)
    {
        $name = $request->get('name');
        $oldUserProfile = clone $userProfile;
        $userProfile->name = $name;
        $userProfile->save();
        $profileUpdatedMail = new ProfileUpdated($oldUserProfile, $userProfile);
        Queue::push(new SendEmailJob($profileUpdatedMail));
        return new UserProfileResource($userProfile);
    }

    /**
     * 5. роут DELETE api/v0/users/profile/{userProfile}
     *
     * @param  UserProfile $userProfile
     *
     * @return JSON
     */
    public function deleteProfile(UserProfile $userProfile)
    {
        $userProfile->delete();
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
        if (!$userProfile) {
            abort(404, "Профиль пользователя с id = $id не найден");
        }
        return new UserProfileResource($userProfile);
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
        if (!$user) {
            abort(404, "Пользователь с id = $id не найден");
        }
        $userProfiles = DB::table('user_profiles')->where('user_id', $id)->get();
        return new UserProfileCollection($userProfiles);
    }

    /**
     * 8. роут GET api/v0/db/users/profiles
     * возвращает все профили по 5 на страницу, если профилей нет - пустой массив
     * Параметры: 1. page обязательно >= 1
     *
     * @param  PageRequest $request
     *
     * @return JSON
     */
    public function showProfilesPerPageDB(PageRequest $request)
    {
        $userProfiles = DB::table('user_profiles')->paginate(self::PROFILES_PER_PAGE)->except(['data']);
        return new UserProfileCollection($userProfiles);
    }

    /**
     * 9. роут PATCH api/v0/db/users/profile/{id}
     * Параметры: 1 name - обновляет имя профиля
     *
     * @param  int $id
     * @param  NameRequest $request
     *
     * @return JSON
     */
    public function updateProfileDB($id, NameRequest $request)
    {
        $name = $request->get('name');
        $userProfile = DB::table('user_profiles')->where('id', $id)->first();
        if (!$userProfile) {
            abort(404, "Профиль пользователя с id = $id не найден");
        }
        DB::table('user_profiles')
            ->where('id', $id)
            ->update(['name' => $name]);
        $updatedUserProfile = DB::table('user_profiles')->where('id', $id)->first();
        return new UserProfileResource($updatedUserProfile);
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
        if (!$userProfile) {
            abort(404, "Профиль пользователя с id = $id не найден");
        }
        DB::table('user_profiles')->where('id', $id)->delete();
        return response()->json(['success' => true]);
    }

    /**
     * 11. роут POST api/v0/users/profile
     * добавляет profile
     *
     * @param  NameRequest $request
     *
     * @return JSON
     */
    public function addProfile(NameRequest $request)
    {
        $name = $request->get('name');
        $userProfile = new UserProfile;
        $userProfile->name = $request->get('name');
        $userProfile->save();
        return new UserProfileResource($userProfile);
    }
}

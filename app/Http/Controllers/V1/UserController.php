<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\UserCredetialsRequest;
use App\Http\Transformers\V1\User\UserTransformer;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected const USERS_PER_PAGE = 5;

    /**
     * 3. GET api/v1/users
     *
     * Middleware: api:auth + admin_only + stop_banned
     *
     * Параметры:
     *
     * api_token
     * page - default = 1 - по 5 на страницу
     *
     * @param  Request $request
     *
     * @return JSON
     */
    public function getUsersCredentials(Request $request)
    {
        $users = User::paginate(self::USERS_PER_PAGE)->except(['data']);
        return response()->json([
            "success" => true,
            "data" => [
                "users" => UserTransformer::transformCollection($users),
            ],
        ]);
    }

    /**
     * 4. PATCH api/v1/user/{user}
     * Обновляем инфу юзера
     *
     * Middleware: api:auth + admin_only + stop_banned
     *
     * Параметры:
     *
     * name
     * role
     * banned - boolean
     *
     * @param  Request $request
     *
     * @return JSON
     */
    public function updateUser(User $user, UserCredetialsRequest $request)
    {
        $user->name = $request->get('name');
        $user->role = $request->get('role');
        $user->banned = $request->get('banned');
        $user->save();
        return response()->json([
            "success" => true,
            "data" => [
                "user" => UserTransformer::transformItem($user),
            ],
        ]);

    }

}

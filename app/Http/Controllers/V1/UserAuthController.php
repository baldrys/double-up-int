<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmailPasswordRequest;
use App\Http\Transformers\V1\User\UserTransformer;
use App\Support\Enums\UserRole;
use App\User;
use BenSampo\Enum\Rules\EnumKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ApiTokenRequest;

class UserAuthController extends Controller
{
    protected const USERS_PER_PAGE = 5;

    /**
     * 1. GET api/v1/auth/login
     * нужно вернуть api_token из таблицы users
     * если комбинация email & пароля неправильная, вернуть: 401 - Unauthorized
     *
     * Параметры:
     *
     * email - string
     * password - string
     *
     * @param  NameRequest $request
     *
     * @return JSON
     */
    public function login(EmailPasswordRequest $request)
    {
        $email = $request->get('email');
        $pass = $request->get('password');
        if (User::where('email', $email)->exists()) {
            $user = User::where('email', $email)->first();
            $auth = Hash::check($pass, $user->password);
            if ($user && $auth) {
                $user->rollApiKey();
                return response()->json([
                    "success" => true,
                    "data" => [
                        "token" => $user->api_token,
                    ],
                ]);
            }
        }
        abort(401, "Unauthorized");
    }

    /**
     * 2. GET api/v1/auth/logout
     * нужно найти юзера по токену (если не найден вернуть 404)
     * затем обновить этот токен через str_random и вернуть true
     *
     * Параметры:
     *
     * api_token
     *
     * @param  Request $request
     *
     * @return JSON
     */
    public function logout(ApiTokenRequest $request)
    {
        if (User::where('api_token', $request->get('api_token'))->exists()) {
            User::where('api_token', $request->get('api_token'))->first()->rollApiKey();
            return response()->json(['success' => true]);
        }
        abort(404, "Пользователь не найден!");
    }

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
    public function getUsersCredentials(ApiTokenRequest $request)
    {
        $this->validate($request, [
            'page' => 'integer|min:1',
        ]);
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
    public function updateUser(User $user, Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'role' => ['required', new EnumKey(UserRole::class)],
            'banned' => 'required|boolean',
        ]);

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

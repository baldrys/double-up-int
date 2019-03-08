<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ApiTokenRequest;
use App\Http\Requests\V1\EmailPasswordRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserAuthController extends Controller
{
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
        return response()->json([
            "success" => false,
            "message" => "Unauthorized",
        ], 401);
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
        return response()->json([
            "success" => false,
            "message" => "Пользователь не найден!",
        ], 404);
    }
}

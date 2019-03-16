<?php

namespace Tests\Unit\Http\Controllers\V1;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserAuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 1. GET api/v1/auth/login
     *
     * @test
     * @throws \Exception
     */
    public function GetApiToken_DataCorrect_Success()
    {
        $password = 'qwerty123';
        $user = factory(User::class)->create([
            'password' => Hash::make($password),
        ]);
        $response = $this->json('GET', 'api/v1/auth/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(200);
        $response->assertJson(["success" => true]);
        $response->assertJsonFragment([
            "token" => User::find($user->id)->api_token,
        ]);
    }

    /**
     * 1. GET api/v1/auth/login
     *
     * @test
     * @throws \Exception
     */
    public function GetApiToken_DataIncorrect_Unauthorized()
    {
        $passwordAcctual = 'qwerty123';
        $passwordFail = 'qwerty1234';
        $user = factory(User::class)->create([
            'password' => Hash::make($passwordAcctual),
        ]);
        $response = $this->json('GET', 'api/v1/auth/login', [
            'email' => $user->email,
            'password' => $passwordFail,
        ]);

        $response->assertStatus(401);
        $response->assertJson(["success" => false]);
    }

    /**
     * 2. GET api/v1/auth/logout
     *
     * @test
     * @throws \Exception
     */
    public function FindByApiTokenAndRollUp_DataCorrect_Success()
    {
        $user = factory(User::class)->create([
            'api_token' => str_random(30),
        ]);
        $response = $this->json('GET', 'api/v1/auth/logout', [
            'api_token' => $user->api_token,
        ]);

        $response->assertStatus(200);
        $response->assertJson(["success" => true]);
    }

    /**
     * 2. GET api/v1/auth/logout
     *
     * @test
     * @throws \Exception
     */
    public function FindByApiTokenAndRollUp_DataInCorrect_NotFound()
    {
        $user = factory(User::class)->create([
            'api_token' => str_random(30),
        ]);
        $response = $this->json('GET', 'api/v1/auth/logout', [
            'api_token' => strrev($user->api_token),
        ]);

        $response->assertStatus(404);
        $response->assertJson(["success" => false]);
    }

}

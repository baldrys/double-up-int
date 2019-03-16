<?php

namespace Tests\Unit\Http\Controllers\V1;

use App\Support\Enums\UserRole;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 3. GET api/v1/users
     *
     * @test
     * @throws \Exception
     */
    public function GetUsersCredentials_DataCorrect_Success()
    {
        $numberOfUsersToCreate = 3;

        $api_token = str_random(30);
        $user = factory(User::class)->create([
            'api_token' => $api_token,
            'role' => UserRole::Admin,
            'banned' => false,
        ]);
        $this->actingAs($user, 'api');
        factory(User::class, $numberOfUsersToCreate - 1)->create();

        $response = $this->json('GET', 'api/v1/users', [
            'api_token' => $user->api_token,
        ]);

        $response->assertStatus(200);
        $response->assertJson(["success" => true]);
        $response->assertJsonCount($numberOfUsersToCreate, 'data.users');
    }

    /**
     * 4. PATCH api/v1/user/{userId}
     *
     * @test
     * @throws \Exception
     */
    public function UpdateUser_DataCorrect_Success()
    {
        $newUserName = str_random(30);
        $newUserRole = UserRole::User;
        $newBanned = 1;
        $api_token = str_random(30);
        $user = factory(User::class)->create([
            'api_token' => $api_token,
            'role' => UserRole::Admin,
            'banned' => false,
        ]);

        $this->actingAs($user, 'api');

        $response = $this->json('PATCH', 'api/v1/user/' . $user->id, [
            'api_token' => $user->api_token,
            'name' => $newUserName,
            'role' => $newUserRole,
            'banned' => $newBanned,
        ]);
        $updatedUser = User::Find($user->id);
        $this->assertEquals($updatedUser->name, $newUserName);
        $this->assertEquals($updatedUser->role, $newUserRole);
        $this->assertEquals($updatedUser->banned, $newBanned);
        $response->assertStatus(200);
        $response->assertJson(["success" => true]);

    }

}

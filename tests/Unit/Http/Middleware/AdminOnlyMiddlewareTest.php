<?php

namespace Tests\Unit\Http\Middleware;

use App\Http\Middleware\AdminOnly;
use App\Support\Enums\UserRole;
use App\User;
use Illuminate\Http\Request;
use Tests\TestCase;

class AdminOnlyMiddlewareTest extends TestCase
{
    /**
     *
     * @test
     * @throws \Exception
     */
    public function CheckIfUserIsAdmin_Admin_Null()
    {
        $user = factory(User::class)->create([
            'role' => UserRole::Admin,
        ]);
        $this->actingAs($user, 'api');
        $request = Request::create('/', 'GET');
        $middleware = new AdminOnly();
        $response = $middleware->handle($request, function () {});
        $this->assertEquals($response, null);
    }

    /**
     *
     * @test
     * @throws \Exception
     */
    public function CheckIfUserIsAdmin_NotAdmin_Forbidden()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');
        $request = Request::create('/', 'GET');
        $middleware = new AdminOnly();
        $response = $middleware->handle($request, function () {});
        $this->assertEquals($response->getStatusCode(), 403);
    }
}

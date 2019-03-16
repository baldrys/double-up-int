<?php

namespace Tests\Unit\Http\Middleware;

use App\Http\Middleware\StopBanned;
use App\User;
use Illuminate\Http\Request;
use Tests\TestCase;

class StopBannedMiddlewareTest extends TestCase
{
    /**
     *
     * @test
     * @throws \Exception
     */
    public function CheckIfUserIsNotBanned_Banned_Null()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');
        $request = Request::create('/', 'GET');
        $middleware = new StopBanned();
        $response = $middleware->handle($request, function () {});
        $this->assertEquals($response, null);
    }

    /**
     *
     * @test
     * @throws \Exception
     */
    public function CheckIfUserIsNotBanned_NotBanned_Forbidden()
    {
        $user = factory(User::class)->create(['banned' => true]);
        $this->actingAs($user, 'api');
        $request = Request::create('/', 'GET');
        $middleware = new StopBanned();
        $response = $middleware->handle($request, function () {});
        $this->assertEquals($response->getStatusCode(), 403);
    }
}

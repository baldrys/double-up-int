<?php

namespace Tests\Unit\Http\Controllers\V0;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\UserGroup;
use App\UserGroups;

class UserGroupsControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 1. POST api/v0/users/group
     * 
     * @test
     * @throws \Exception
     */
    public function CreateGroup_DataCorrect_Success()
    {
        $testGroupName = 'groupName';
        $response = $this->post('api/v0/users/group', ['name' => $testGroupName]);
        $response->assertStatus(201);
        $response->assertJson(["success" => true]);
        $this->assertDatabaseHas('user_group', [
            'name' => $testGroupName
        ]);
    }

    /**
     * 2. GET api/v0/user/{user}/groups
     * 
     * @test
     * @throws \Exception
     */
    public function GetGroups_DataCorrect_Success()
    {
        $numberOfGroupsToCreate = 3;
        $user = factory(User::class)->create();
        $groups = factory(UserGroup::class, $numberOfGroupsToCreate)->create();
        $user->groups()->attach($groups);
        $response = $this->get('api/v0/user/'.$user->id.'/groups');
        $response->assertStatus(200);
        $response->assertJsonCount($numberOfGroupsToCreate, 'data.groups');
    }


    /**
     * 3. DELETE api/v0/users/groups/{group}
     * 
     * @test
     * @throws \Exception
     */
    public function DeleteGroup_DataCorrect_Success()
    {
        $group = factory(UserGroup::class)->create();
        $response = $this->delete('api/v0/users/groups/'.$group->id);
        $response->assertStatus(200);
        $response->assertJson(["success" => true]);
    }


    /**
     * 4. POST api/v0/user/{user}/group/{group}
     * 
     * @test
     * @throws \Exception
     */
    public function AddUserToGroup_UserNotInGroup_Success()
    {
        $user = factory(User::class)->create();
        $group = factory(UserGroup::class)->create();
        $response = $this->post('api/v0/user/'.$user->id.'/group/'.$group->id);
        $this->assertTrue(UserGroups::where('user_id', $user->id)->where('group_id', $group->id)->exists());
        $response->assertStatus(200);
        $response->assertJson(["success" => true]);
    }

    /**
     * 4. POST api/v0/user/{user}/group/{group}
     * 
     * @test
     * @throws \Exception
     */
    public function AddUserToGroup_UserInGroup_BadRequest()
    {
        $user = factory(User::class)->create();
        $group = factory(UserGroup::class)->create();
        $user->groups()->save($group);
        $response = $this->post('api/v0/user/'.$user->id.'/group/'.$group->id);
        $response->assertStatus(400);
        $response->assertJson(["success" => False]);
    }

    /**
     * 5. DELETE api/v0/user/{userId}/group/{groupId}
     * 
     * @test
     * @throws \Exception
     */
    public function DeleteUserFromGroup_UserInGroup_Success()
    {
        $user = factory(User::class)->create();
        $group = factory(UserGroup::class)->create();
        $user->groups()->save($group);
        $response = $this->delete('api/v0/user/'.$user->id.'/group/'.$group->id);
        $this->assertFalse(UserGroups::where('user_id', $user->id)->where('group_id', $group->id)->exists());
        $response->assertStatus(200);
        $response->assertJson(["success" => True]);
    }

    /**
     * 5. DELETE api/v0/user/{userId}/group/{groupId}
     * 
     * @test
     * @throws \Exception
     */
    public function DeleteUserFromGroup_UserNotInGroup_BadRequest()
    {
        $user = factory(User::class)->create();
        $group = factory(UserGroup::class)->create();
        $response = $this->delete('api/v0/user/'.$user->id.'/group/'.$group->id);
        $response->assertStatus(400);
        $response->assertJson(["success" => False]);
    }

    
    /**
     * 6. роут PATCH api/v0/users/group/{group}
     * 
     * @test
     * @throws \Exception
     */
    public function UpdateGroup_DataCorrect_Success()
    {
        $testGroupName = 'groupName';
        $group = factory(UserGroup::class)->create();
        $response = $this->patch('api/v0/users/group/'.$group->id, ['name' => $testGroupName]);
        $this->assertEquals(UserGroup::find( $group->id)->name, $testGroupName);
        $response->assertStatus(200);
        $response->assertJson(["success" => True]);
    }

}

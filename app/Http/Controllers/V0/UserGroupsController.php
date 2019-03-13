<?php

namespace App\Http\Controllers\V0;

use App\Http\Controllers\Controller;
use App\Http\Requests\NameRequest;
use App\Http\Resources\CreatedUserGroup;
use App\Http\Resources\UserGroupCollection;
use App\Jobs\SendEmailJob;
use App\Mail\GroupAdded;
use App\User;
use App\UserGroup;
use App\UserGroups;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Queue;

class UserGroupsController extends Controller
{

    /**
     * 1. POST api/v0/users/group
     * добавляем группу
     *
     * @param  NameRequest $request
     *
     * @return JSON
     */
    public function addGroup(NameRequest $request)
    {
        $group = new UserGroup;
        $group->name = $request->get('name');
        $group->save();
        User::notifyAllAdmins(new GroupAdded($group));
        return new CreatedUserGroup($group);
    }

    /**
     * 2. GET api/v0/user/{user}/groups
     * получаем группы пользователя
     *
     * @param  User $user
     *
     * @return JSON
     */
    public function showGroups(User $user)
    {
        $groups = $user->groups()->get();
        return new UserGroupCollection($groups);
    }

    /**
     * 3. DELETE api/v0/users/groups/{group}
     * удаляет группу
     *
     * @param  UserGroup $group
     *
     * @return JSON
     */
    public function deleteGroup(UserGroup $group)
    {
        $group->delete();
        return response()->json(['success' => true]);
    }

    /**
     * 4. POST api/v0/user/{user}/group/{group}
     * добавляет пользователя к группе
     *
     * @param  User $user
     * @param  UserGroup $group
     *
     * @return JSON
     */
    public function addUserToGroup(User $user, UserGroup $group)
    {
        if (UserGroups::where('user_id', $user->id)->where('group_id', $group->id)->exists()) {
            return response()->json([
                "success" => false,
                "message" => "Пользователь $user->name уже в группе $group->name!",
            ], 400);
        }
        $userGroup = new UserGroups;
        $userGroup->user_id = $user->id;
        $userGroup->group_id = $group->id;
        $userGroup->save();
        return response()->json(['success' => true]);
    }

    /**
     * 5. DELETE api/v0/user/{userId}/group/{groupId}
     * убирает пользователя из группы
     *
     * @param  User $user
     * @param  UserGroup $group
     *
     * @return JSON
     */
    public function deleteUserFromGroup(User $user, UserGroup $group)
    {
        $userGroup = UserGroups::where('user_id', $user->id)->where('group_id', $group->id);
        if (!$userGroup->exists()) {
            return response()->json([
                "success" => false,
                "message" => "Пользователя $user->name нет в группе $group->name!",
            ], 400);
        }
        $userGroup->delete();
        return response()->json(['success' => true]);
    }

    /**
     * 6. роут PATCH api/v0/users/group/{group}
     * Параметры: 1 name - обновляет имя профиля
     *
     * @param  NameRequest $userProfile
     * @param  Request $request
     *
     * @return JSON
     */
    public function updateGroup(UserGroup $group, NameRequest $request)
    {
        $name = $request->get('name');
        $group->name = $name;
        $group->save();
        return new CreatedUserGroup($group);
    }

}

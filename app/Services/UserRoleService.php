<?php

namespace App\Services;

use App\Http\Resources\UsersResource;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserRoleService extends BaseServices
{
    use HttpResponses;

    /**
     * store new role
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeRole(Request $request)
    {
        return Role::create(['guard_name' => 'web', 'name' => $request->name]);
    }

    /**
     * assign role to user model
     *
     * @param  \App\Models\User  $user
     * @param  \Spatie\Permission\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function assignUserRole(User $user, Role $role)
    {
        if ($user->hasAnyRole($role->name)) {
            return $this->error(null, 'user has specified role', 409);
        }
        $userRole =  $user->syncRoles($role->name);

        if ($userRole) {
            return (new UsersResource($user))->additional([
                'data' => [
                    'role' => $role->name,
                    'message' => 'role has been assigned successfully'
                ]
            ]);
        }
    }

    /**
     * remove role from user model
     *
     * @param  \App\Models\User  $user
     * @param  \Spatie\Permission\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function dropUserRole(User $user, Role $role)
    {
        if (!$user->hasAnyRole($role->name)) {
            return $this->error(null, 'user does not have specified role', 409);
        }

        $userRole = $user->removeRole($role->name);

        if ($userRole) {
            return (new UsersResource($user))->additional([
                'data' => [
                    'role' => null,
                    'message' => 'role has been removed successfully'
                ]
            ]);
        }
    }
}

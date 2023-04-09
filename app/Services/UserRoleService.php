<?php

namespace App\Services;

use App\Http\Resources\PermissionsResource;
use App\Http\Resources\UsersResource;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserRoleService extends BaseServices
{
    use HttpResponses;

    protected $model;

    public function __construct()
    {
        $this->model = new Role();
    }

    //store new role
    public function storeRole($request)
    {
        $role = $this->model->create(['guard_name' => $request->guard_name, 'name' => $request->name]);
        $role->givePermissionTo($request->permissions);
        return $role;
    }

    //update new role
    public function updateRole($id, $request)
    {
        $role = $this->model->findOrFail($id);
        $role->update(['guard_name' => $request->guard_name, 'name' => $request->name]);
        $role->syncPermissions($request->permissions);
        return $role;
    }

    //delete role
    public function deleteRole($id)
    {
        $role = $this->model->findOrFail($id);
        $role->syncPermissions([]);
        $role->delete();
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
                    'message' => 'role has been assigned successfully',
                    'permissions' => PermissionsResource::collection($role->permissions),
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

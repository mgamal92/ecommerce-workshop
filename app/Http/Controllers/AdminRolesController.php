<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminRolesRequest;
use App\Http\Resources\RolesResource;
use App\Http\Resources\UsersResource;
use App\Models\User;
use App\Services\UserRoleService;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AdminRolesController extends Controller
{
    use HttpResponses;

    protected UserRoleService $userRoleService;

    protected $model;

    public function __construct(UserRoleService $userRoleService)
    {
        $this->userRoleService = $userRoleService;
        $this->model = new Role();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return all roles
        return RolesResource::collection($this->userRoleService->retrieve($this->model));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminRolesRequest $request)
    {
        $request->validated();

        $role = $this->userRoleService->storeRole($request);

        return new RolesResource($role);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        return new RolesResource($this->userRoleService->show($this->model, $role->id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:3', "unique:roles,name,$role->id"]
        ]);
        return new RolesResource($this->userRoleService->update($this->model, $role->id, $request->toArray()));
    }

    /**
     * Assign role to user
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function assignRole(User $user, Role $role)
    {
        return $this->userRoleService->assignUserRole($user, $role);
    }

    /**
     * Remove user role
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function dropRole(User $user, Role $role)
    {
        return $this->userRoleService->dropUserRole($user, $role);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $removeRole = $this->userRoleService->delete($this->model, $role->id);

        if ($removeRole) {
            return $this->success(null, 'role removed successfully');
        }
    }
}

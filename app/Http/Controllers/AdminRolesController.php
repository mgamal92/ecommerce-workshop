<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAdminRolesRequest;
use App\Http\Requests\UpdateAdminRolesRequest;
use App\Http\Resources\RolesResource;
use App\Http\Resources\UsersResource;
use App\Models\User;
use App\Services\UserRoleService;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
    public function store(CreateAdminRolesRequest $request)
    {
        try {
            DB::beginTransaction();
                $role = $this->userRoleService->storeRole($request);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

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
    public function update(UpdateAdminRolesRequest $request, Role $role)
    {
        try {
            DB::beginTransaction();
                $role = $this->userRoleService->updateRole($role->id, $request);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return new RolesResource($role);
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
        try {
            DB::beginTransaction();
                $this->userRoleService->deleteRole($role->id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $this->success(null, 'role with it\'s permissions removed successfully');
    }
}

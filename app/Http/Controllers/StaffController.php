<?php

namespace App\Http\Controllers;

use App\Http\Resources\UsersResource;
use App\Models\User;
use App\Services\StaffService;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    use HttpResponses;

    public function __construct(protected StaffService $service, protected User $model)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return all customers
        return UsersResource::collection($this->service->retrieve($this->model));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //NOTE no validations applied
        $user = $this->service->store($this->model, $request->toArray());

        return new UsersResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $update = $this->service->update($this->model, $user->id, $request->toArray());

        return new UsersResource($update);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return new UsersResource($this->service->show($this->model, $user->id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $delete = $this->service->delete($this->model, $user->id);

        if (!$delete) {
            return $this->success(null, "User Deleted Successfully", 200);
        }
    }
}

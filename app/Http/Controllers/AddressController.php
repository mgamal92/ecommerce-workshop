<?php

namespace App\Http\Controllers;

use App\Http\Resources\AddressesResource;
use App\Models\Address;
use App\Models\Customer;
use App\Services\AddressService;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    protected $model;

    public function __construct(protected AddressService $service)
    {
        $this->model = new Address();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return AddressesResource::collection($this->service->retrieve($this->model));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Address $address)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function destroy(Address $address)
    {
        //
    }
}

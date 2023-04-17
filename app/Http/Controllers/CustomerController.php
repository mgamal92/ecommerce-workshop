<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomersResource;
use App\Models\Customer;
use App\Services\CustomerService;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    use HttpResponses;

    protected CustomerService $customerService;

    protected $model;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
        $this->model = new Customer();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return all customers
        return CustomersResource::collection($this->customerService->retrieve($this->model));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($request, $customer)
    {
        return $this->customerService->store($customer, $request->toArray());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        $updateCustomer = $this->customerService->update($this->model, $customer->id, $request->toArray());

        return new CustomersResource($updateCustomer);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        return new CustomersResource($this->customerService->show($this->model, $customer->id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        $deleteCustomer = $this->customerService->delete($this->model, $customer->id);

        if (!$deleteCustomer) {
            return $this->success(null, "Customer Deleted Successfully", 200);
        }
    }

    /**
     * Customer can create new address.
     *
     * @return CustomersResource
     */
    public function newAddress(Request $request)
    {
        if (count(auth()->user()->address) == 7) {
            return $this->error(null, "Maximum limit of seven addresses exceeded. Please remove any unnecessary addresses before adding a new one", 405);
        }
        return new CustomersResource($this->customerService->addAddress($request));
    }
}

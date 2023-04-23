<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomersResource;
use App\Http\Resources\OrdersResource;
use App\Models\Address;
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
    public function update(Request $request, Customer $customer, $address)
    {
        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', "unique:customers,email,$customer->id"],
            'address' => ['nullable', 'string'],
            'building_no' => ['nullable', 'integer'],
            'country' => ['nullable', 'string'],
            'country_code' => ['nullable', 'string'],
            'city' => ['nullable', 'string'],
            'avatar' => ['nullable', 'image', 'max:1000']
        ]);
        $validated['address_id'] = $address;
        return new CustomersResource($this->customerService->update($this->model, $customer->id, $validated));
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

    public function removeAddress(Customer $customer, $address)
    {
        if (count($customer->address) <= 1) {
            return $this->error(null, 'You must have at least one address saved.', 405);
        }
        return $this->customerService->deleteAddress($customer, $address);
    }

    public function profile()
    {
        $orders = OrdersResource::collection(auth()->user()->order);
        return (new CustomersResource(auth()->user()))->additional(['total_orders' => $orders]);
    }
}

<?php

namespace App\Services;

use App\Models\Address;
use App\Models\Customer;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerService extends BaseServices
{
    use HttpResponses;
    public function store($model, array $data)
    {
        $address = new Address();
        $address->customer_id = $model->id;
        $address->address = $data['address'];
        $address->building_no = $data['building_no'];
        $address->country = $data['country'];
        $address->country_code = $data['country_code'];
        $address->city = $data['city'];
        $address->save();
    }

    public function addAddress(Request $request)
    {
        $customer = Customer::findOrFail(Auth::user()->id);

        $address = $customer->address()->create([
            'address' => $request->address,
            'building_no' => $request->building_no,
            'city' => $request->city,
            'country' => $request->country,
            'country_code' => $request->country_code
        ]);

        if ($address) {
            return $customer;
        }
    }

    public function update($model, int $id, array $data)
    {
        $customer = Customer::findOrFail($id);
        $customer->update([
            'name' => $data['name'],
            'email' => $data['email']
        ]);
        //update address
        Address::whereId($data['address_id'])->whereCustomerId($id)->update([
            'address' => $data['address'],
            'building_no' => $data['building_no'],
            'city' => $data['city'],
            'country' => $data['country'],
            'country_code' => $data['country_code']
        ]);
        if ($customer) {
            return $customer;
        }
    }

    public function deleteAddress($model, $address)
    {
        $customerAddress = Address::whereId($address)->whereCustomerId($model->id)->firstOrFail();

        $customerAddress->delete();

        if ($customerAddress) {
            return $this->success(null, 'address deleted successfully', 201);
        }
    }
}

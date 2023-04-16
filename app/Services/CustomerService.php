<?php

namespace App\Services;

use App\Models\Address;

class CustomerService extends BaseServices
{
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
}

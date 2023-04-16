<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = ['address', 'building_no', 'city', 'country', 'country_code'];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}

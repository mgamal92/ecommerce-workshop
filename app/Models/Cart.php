<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'products', 'offer_sent'];

    protected $casts = [
        'products' => 'array'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

}

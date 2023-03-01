<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'products'];

    protected $casts = [
        'products' => 'json',
    ];

    public function customer() {
        $this->belongsTo(Customer::class, 'customer_id');
    }
}

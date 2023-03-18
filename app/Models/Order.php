<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    const STATUS_SUCCESSFUL = 1;
    const  STATUS_PENDING = 0;
    const STATUS_FAILED = 2;
    const CARD_PAYMENT = 'card';
    const WALLET_PAYMENT = 'wallet';

    protected $fillable = [
        'order_id',
        'user_id',
        'status',
        'email',
        'fname',
        'lname',
        'street',
        'building',
        'floor',
        'apartment',
        'additional_info',
        'phone',
        'payment_method',
        'shipping_fees',
        'total_amount',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'products_count'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function customer()
    {
        return $this->belongsToMany(Customer::class, 'customer_category', 'category_id', 'customer_id');
    }
}

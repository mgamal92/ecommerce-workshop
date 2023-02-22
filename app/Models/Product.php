<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'name', 'quantity', 'price'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeFilter($query, $category, $name, $price) {
        return $query
            ->when($category, function ($query, $category) {
                $query->WhereHas('category', function ($query) use ($category) {
                    $query->where('name', $category);
                });
            })
            ->when($name, function ($query, $name) {
                $query->where('name', 'like', '%'.$name.'%');
            })
            ->when($price, function ($query, $price) {
                $query->where('price', $price);
            });
    }
}

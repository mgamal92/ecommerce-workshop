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

    public function scopeFilter($query, $category, $name, $price)
    {
        return $query
            ->when($category, function ($query, $category) {
                $query->WhereHas('category', function ($query) use ($category) {
                    $query->where('name', $category);
                });
            })
            ->when($name, function ($query, $name) {
                $query->where('name', 'like', '%' . $name . '%');
            })
            ->when($price, function ($query, $price) {
                $query->where('price', $price);
            });
    }

    /**
     * Using 'boot()' method to register a model event listener.
     * Listen to new 'Product' instances being created or deleted.
     * Automatically update the products_count
     */
    public static function boot()
    {
        parent::boot();

        static::created(function ($product) {
            Category::whereId($product->category_id)
                ->increment('products_count');
        });

        static::deleted(function ($product) {
            Category::whereId($product->category_id)
                ->decrement('products_count');
        });
    }
    public function scopeSearch($query, $val)
    {
        return $query
            ->where('name', 'like', '%' . $val . '%')
            ->OrWhere('price', 'like', '%' . $val . '%')
            ->OrWhere('created_at', 'like', '%' . $val . '%')
            ->OrWhereHas('category', function ($query) use ($val) {
                $query->where('name', 'like', '%' . $val . '%');
            });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Baro\PipelineQueryCollection\Concerns\Filterable;
use Baro\PipelineQueryCollection\Contracts\CanFilterContract;
use Baro\PipelineQueryCollection\RelativeFilter;
use Baro\PipelineQueryCollection\RelationFilter;
use Baro\PipelineQueryCollection\ExactFilter;


class Product extends Model implements CanFilterContract
{
    use HasFactory, Filterable;

    protected $fillable = ['category_id', 'name', 'quantity', 'price'];

    public function getFilters(): array
    {
        return [
            new RelativeFilter('name'),
            new ExactFilter('price'),
            new RelationFilter('category', 'id'),
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

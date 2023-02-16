<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Baro\PipelineQueryCollection\Concerns\Filterable;
use Baro\PipelineQueryCollection\Contracts\CanFilterContract;
use Baro\PipelineQueryCollection\RelativeFilter;


class Product extends Model implements CanFilterContract
{
    use HasFactory, Filterable;

    public function getFilters(): array
    {
        return [
            new RelativeFilter('name'),
            new RelativeFilter('price'),
            new RelativeFilter('category'),
        ];
    }
}

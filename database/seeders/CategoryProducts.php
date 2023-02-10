<?php

namespace Database\Seeders;

use App\Models\CategoryProducts as ModelsCategoryProducts;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryProducts extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 0; $i < 80; $i++) {
        ModelsCategoryProducts::create([
            'category_id' => rand(1,20),
            'product_id' => rand(1,20)
        ]);
        }

    }
}

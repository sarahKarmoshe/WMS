<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            'name'=> Str::random(8),
            'measurement_unit',
            'space',
            'products_number_by_space',
            'photo',
            'min_quantity',
            'max_quantity',
            'exist_quantity',
            'department_id'=>1,
            'category_id'=>1
        ]);
    }
}

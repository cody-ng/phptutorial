<?php

namespace Database\Seeders;

use App\Models\Product;

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Product::factory()
        //         ->count(50)
        //         ->create();

        $dt = Carbon::now();
        $products = [
            [
                'name' => 'Apple',
                'price' => 3,
                'description' => 'one pound of apple',
                'created_at' => $dt, 
                'updated_at' => $dt
            ],
            [
                'name' => 'Orange',
                'price' => 5,
                'description' => 'one pound of orange',
                'created_at' => $dt, 
                'updated_at' => $dt
            ],
            [
                'name' => 'Grape',
                'price' => 2,
                'description' => 'a pound of grape',
                'created_at' => $dt, 
                'updated_at' => $dt
            ]
        ];

        // for bulk insert:
        // insert() doesn't update 'created_at' or 'updated_at' fields
        Product::insert($products);

    }
}
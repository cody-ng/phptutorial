<?php

namespace Database\Seeders;

use App\Models\Customer;

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Customer::factory()
        //         ->count(50)
        //         ->create();

        $dt = Carbon::now();

        $customers =[
            [
                'last_name' => 'Smith',
                'first_name' => 'Joe',
                'created_at' => $dt, 
                'updated_at' => $dt
                // 'created_at'=>date('Y-m-d H:i:s'),
                // 'updated_at'=> date('Y-m-d H:i:s')                
            ],
            [
                'last_name' => 'Poppin',
                'first_name' => 'Mary',
                'created_at' => $dt, 
                'updated_at' => $dt
            ],
            [
                'last_name' => 'Jordan',
                'first_name' => 'Philip',
                'created_at' => $dt, 
                'updated_at' => $dt
            ],
        ];

        // for bulk insert:
        // insert() doesn't update 'created_at' or 'updated_at' fields
        Customer::insert($customers);

    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroseryStoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [

            [
                'ingredient' => 'tomato',
                'quantity' => 5,
                'status_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'code' => '001',
            ],
            [
                'ingredient' => 'lemon',
                'quantity' => 5,
                'status_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'code' => '002',
            ],
            [
                'ingredient' => 'potato',
                'quantity' => 5,
                'status_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'code' => '003',
            ],
            [
                'ingredient' => 'rice',
                'quantity' => 5,
                'status_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'code' => '004',
            ],
            [
                'ingredient' => 'ketchup',
                'quantity' => 5,
                'status_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'code' => '005',
            ],
            [
                'ingredient' => 'lettuce',
                'quantity' => 5,
                'status_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'code' => '006',
            ],
            [
                'ingredient' => 'onion',
                'quantity' => 5,
                'status_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'code' => '007',
            ],
            [
                'ingredient' => 'cheese',
                'quantity' => 5,
                'status_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'code' => '008',
            ],
            [
                'ingredient' => 'meat',
                'quantity' => 5,
                'status_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'code' => '009',
            ],
            [
                'ingredient' => 'chicken',
                'quantity' => 5,
                'status_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'code' => '010',
            ],
        ];

        DB::table('grocery_stores')->insert($data);
    }
}

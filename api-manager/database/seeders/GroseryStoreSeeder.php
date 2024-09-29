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
            ],
            [
                'ingredient' => 'lemon',
                'quantity' => 5,
                'status_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ingredient' => 'potato',
                'quantity' => 5,
                'status_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ingredient' => 'rice',
                'quantity' => 5,
                'status_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ingredient' => 'ketchup',
                'quantity' => 5,
                'status_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ingredient' => 'lettuce',
                'quantity' => 5,
                'status_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ingredient' => 'onion',
                'quantity' => 5,
                'status_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ingredient' => 'cheese',
                'quantity' => 5,
                'status_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ingredient' => 'meat',
                'quantity' => 5,
                'status_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ingredient' => 'chicken',
                'quantity' => 5,
                'status_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('grocery_stores')->insert($data);
    }
}

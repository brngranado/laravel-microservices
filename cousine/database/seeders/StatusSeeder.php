<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [

            [
                'name' => 'EXISTE',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'NO EXISTE',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ACTIVO',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'INACTIVO',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'EN PROCESO',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'FINALIZADO',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'A LA ESPERA DE INGREDIENTES',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('statuses')->insert($data);
    }
}

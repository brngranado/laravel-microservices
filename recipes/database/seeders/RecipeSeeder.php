<?php

namespace Database\Seeders;

use App\Models\RecipeGrosery;
use App\Models\Recipes;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataRecipe = [

            [
                'name' => 'Ensalada de Pollo y Lechuga',
                'status_id' => 1,
                'ingredients' => [
                  [
                    'id'=>'010',
                    'quantity' => 3
                  ],
                  [
                    'id'=>'006',
                    'quantity' => 1
                  ],
                  [
                    'id'=>'001',
                    'quantity' => 1
                  ],
                  [
                    'id'=>'007',
                    'quantity' =>2
                  ],
                  [
                    'id'=>'002',
                    'quantity' => 2
                  ],
                ]
            ],
            [
                'name' => 'Patatas al Horno con Ketchup',
                'status_id' => 1,
                'ingredients' => 
                [
                    ['id'=>'003', 'quantity' => 2],
                    ['id'=>'005', 'quantity' => 1]
                ]
            ],
            [
                'name' => 'Arroz Frito con Pollo y Ketchup',
                'status_id' => 1,
                'ingredients' => 
                [
                    ['id'=>'004', 'quantity' => 1],
                    ['id'=>'001', 'quantity' =>2],
                    ['id'=>'007', 'quantity' => 1],
                    ['id'=>'005', 'quantity' => 2],
                ]
            ],
            [
                'name' => 'Pastel de Carne con Ketchup',   
                'status_id' => 1,
                'ingredients' => 
                [
                    ['id'=>'009', 'quantity' => 1],
                    ['id'=>'007', 'quantity' => 1],
                    ['id'=>'005', 'quantity' => 1],
                ]
            ],
            [
                'name' => 'Tacos de Carne con Salsa de Tomate',   
                'status_id' => 1,
                'ingredients' => 
                [
                    ['id'=>'009', 'quantity' => 1],
                    ['id'=>'007', 'quantity' => 1],
                    ['id'=>'001', 'quantity' => 1],
                    ['id'=>'006', 'quantity' => 1],
                ]
            ],
            [
                'name' => 'Quesadillas de Queso y Cebolla',   
                'status_id' => 1,
                'ingredients' => 
                [
                    ['id'=>'008', 'quantity' => 1],
                    ['id'=>'007', 'quantity' => 1],
                ]
            ],
        ];

        foreach ($dataRecipe as $recipe) {
            $recipeSave = Recipes::create([
                'name' => $recipe['name'],
                'status_id' => $recipe['status_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            foreach ($recipe['ingredients'] as $ingredient) {
                RecipeGrosery::create([
                    'recipe_id' => $recipeSave->id, 
                    'grocery_store_id' => $ingredient['id'],
                    'quantity' => $ingredient['quantity'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
                
        }


    }
}

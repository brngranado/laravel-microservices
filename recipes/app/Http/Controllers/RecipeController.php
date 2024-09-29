<?php

namespace App\Http\Controllers;

use App\Models\RecipeGrosery;
use App\Models\Recipes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Recipes::with('ingredients')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public static function store($dataRabbitMq)
    {
        //TODO: verificar al microservicio de bodega si existen los items
        $recipeSave = Recipes::create([
            'name' => $dataRabbitMq['name'],
            'status_id' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        foreach ($dataRabbitMq['ingredients'] as $ingredient) {
            RecipeGrosery::create([
                'recipe_id' => $recipeSave->id, 
                'grosery_store_id' => $ingredient,
            ]);
        }

    
    }

    /**
     * Display the specified resource.
     */
    public  function show(string $id)
    {
        $recipe =  Recipes::find($id);

        if (!$recipe) {
            return response()->json(['message' => 'Cousine not found'], 404);
        }
        return response()->json($recipe, 200); 
    }

    public static function findRecipeWithGroseryStore(string $id){

        $recipe = Recipes::with('ingredients')->find($id);
        return $recipe;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return response()->json(['message' => 'Recipe edit'], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public static  function update(Request $request, string $id)
    {
        return response()->json(['message' => 'Recipe update'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return response()->json(['message' => 'Recipe destroy'], 200);
    }
}

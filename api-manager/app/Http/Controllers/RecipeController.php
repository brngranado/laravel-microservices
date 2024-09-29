<?php

namespace App\Http\Controllers;

use App\Jobs\Recipes\CreateRecipeJob;
use App\Jobs\Recipes\DeleteRecipeJob;
use App\Jobs\Recipes\FindOneRecipeJob;
use App\Jobs\Recipes\UpdateRecipeJob;
use App\Jobs\Recipes\GetRecipeJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = Http::get('http://recipes.test/api/recipe');
        if ($response->successful()) {
            return response()->json($response->json(), 200);
        } else {
            return response()->json(['error' => 'No se pudieron obtener los datos'], $response->status());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Despachar el job para crear una nueva receta
        $recipe = CreateRecipeJob::dispatch($request->all());

        return response()->json($recipe, 201); // Retornar la receta creada
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Despachar el job para encontrar una receta específica por ID
        $response = Http::get('http://recipes.test/api/recipe/'.$id);
        if ($response->successful()) {
            return response()->json($response->json(), 200);
        } else {
            return response()->json(['error' => 'No se pudieron obtener los datos'], $response->status());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Preparar los datos para la actualización
        $payload = [
            'request' => $request->all(),
            'id' => $id,
        ];
        
        // Despachar el job para actualizar una receta específica
        $recipe = UpdateRecipeJob::dispatch($payload);

        return response()->json($recipe, 200); // Retornar la receta actualizada
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Despachar el job para eliminar una receta específica
        DeleteRecipeJob::dispatch($id);
        
        return response()->json(['message' => 'Recipe deleted'], 200); // Mensaje de éxito
    }
}
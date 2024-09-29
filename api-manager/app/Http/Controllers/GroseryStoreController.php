<?php

namespace App\Http\Controllers;

use App\Jobs\GroceryStore\CreateGroceryItemJob;
use App\Jobs\GroceryStore\DeleteGroceryItemJob;
use App\Jobs\GroceryStore\GetGroceryItemJob;
use App\Jobs\GroceryStore\UpdateGroceryItemJob;
use App\Jobs\GroseryStore\FindOneGroceryItemJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GroseryStoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = Http::get('http://grocery-store.test/api/grocery-store');
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
        // Despachar el job para crear un nuevo item en el grocery store
        $groceryItem = CreateGroceryItemJob::dispatch($request->all());

        return response()->json($groceryItem, 201); // Retornar el item creado
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $response = Http::get('http://grocery-store.test/api/grocery-store/'.$id);
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
        
        // Despachar el job para actualizar un item específico
        $groceryItem = UpdateGroceryItemJob::dispatch($payload);

        return response()->json($groceryItem, 200); // Retornar el item actualizado
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Despachar el job para eliminar un item específico
        DeleteGroceryItemJob::dispatch($id);
        
        return response()->json(['message' => 'Grocery Item deleted'], 200); // Mensaje de éxito
    }
}
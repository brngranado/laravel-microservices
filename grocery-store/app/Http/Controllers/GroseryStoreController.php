<?php

namespace App\Http\Controllers;

use App\Models\GroceryStore;
use App\Services\rabbitmq\SendMQ;
use Illuminate\Http\Request;

class GroseryStoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       return GroceryStore::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public static function store($request)
    {
        $allGroceries = GroceryStore::all()->keyBy('code');
        $cousineId = $request['cousine_id'];
        foreach ($request['ingredients'] as $ingredient) {
            $groceryStoreId = $ingredient['grocery_store_id'];
            $requestedQuantity = $ingredient['quantity'];
            if (isset($allGroceries[$groceryStoreId])) {
                $grocery = $allGroceries[$groceryStoreId];
                if($grocery->quantity > 0) {
                    $newQuantity = $grocery->quantity - $requestedQuantity;
                    if ($newQuantity >= 0) {
                        $grocery->quantity = $newQuantity;
                        $grocery->save(); // Save the updated grocery item
                    } else {
                        return response()->json(['message' => 'Insufficient quantity for grocery store ID: ' . $groceryStoreId], 400);
                    }
                }else {
                    $payload = [
                        'status_id' => 7,
                        'id' => $cousineId
                    ];
                    // Despachar el job para actualizar una receta específica
                    SendMQ::handle('send_update_cousine_queue','send_update_cousine_exchange','cousine_key_update', $payload);
                    SendMQ::close();
                }
               
            } else {
                return response()->json(['message' => 'Grocery store ID: ' . $groceryStoreId . ' not found.'], 404);
            }
        }
    
        return response()->json(['message' => 'Grocery Store updated successfully'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(['message' => 'Grosery Store show'], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return response()->json(['message' => 'Grosery Store edit'], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $cousine = GroceryStore::find($id);

        $attributesToUpdate = [];

        if (isset($request['quantity'])) {
            $attributesToUpdate['quantity'] = $request['quantity'];
        }
    
        $cousine->update($attributesToUpdate);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return response()->json(['message' => 'Grosery Store destroy'], 200);
    }
}

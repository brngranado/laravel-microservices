<?php

namespace App\Http\Controllers;

use App\Jobs\JobSend; // Asegúrate de que este job esté implementado
use App\Models\Cousine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CousineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public static function index()
    {
        return Cousine::whereIn('status_id', [5, 6, 7])->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public static function store($dataRabbitMq)
    {

        // Crear un nuevo registro de Cousine
        return Cousine::create([
            'order_number' => $dataRabbitMq['order_number'],
            'status_id' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public static function show(string $id)
    {
        $cousine = Cousine::find($id); // Buscar el registro por ID

        if (!$cousine) {
            return response()->json(['message' => 'Cousine not found'], 404);
        }

        return response()->json($cousine, 200); // Retornar el registro encontrado
    }
    /**
     * Update the specified resource in storage.
     */
    public static function update($request)
    {
        $cousine = Cousine::find($request['id']);

        $attributesToUpdate = [];

        if (isset($request['order_number'])) {
            $attributesToUpdate['order_number'] = $request['order_number'];
        }
    
        if (isset($request['recipe_id'])) {
            $attributesToUpdate['recipe_id'] = $request['recipe_id'];
        }

          
        if (isset($request['status_id'])) {
            $attributesToUpdate['status_id'] = $request['status_id'];
        }
    
        $cousine->update($attributesToUpdate);
    }

    /**
     * Remove the specified resource from storage.
     */
    public static function destroy(string $id)
    {
        $cousine = Cousine::find($id); // Buscar el registro por ID

        if (!$cousine) {
            return response()->json(['message' => 'Cousine not found'], 404);
        }

        $cousine->delete(); // Eliminar el registro

        return response()->json(['message' => 'Cousine deleted successfully'], 200); // Mensaje de éxito
    }
}
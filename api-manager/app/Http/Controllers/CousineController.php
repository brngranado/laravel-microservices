<?php

namespace App\Http\Controllers;

use App\Jobs\Cousine\CreateCousineJob;
use App\Jobs\Cousine\DeleteCousineJob;
use App\Jobs\Cousine\FindOneCousineJob;
use App\Jobs\Cousine\GetCousineJob;
use App\Jobs\Cousine\UpdateCousineJob;
use App\Models\Cousine;
use App\Services\rabbitmq\ConsumeMQ;
use App\Services\rabbitmq\SendMQ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CousineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = Http::get('http://nginx/api/cousine');
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
        SendMQ::handle('send_store_cousine_queue','send_store_cousine_exchange','cousine_key', $request->all());
        SendMQ::close();
        
        return response()->json(['message' => 'Mensaje enviado correctamente']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $response = Http::get('http://nginx/api/cousine/'.$id);
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
        $payload = [
            'order_number' => $request->order_number,
            'status_id' => $request->status_id,
            'id' => $id
        ];

        // Despachar el job para actualizar una receta específica
        SendMQ::handle('send_update_cousine_queue','send_update_cousine_exchange','cousine_key_update', $payload);
        SendMQ::close();
    }

    public function updateWithIngredientsUpdated(Request $request, string $id)
    {
        $payload = [
            'order_number' => $request->order_number,
            'status_id' => $request->status_id,
            'recipe_id' => $request->recipe_id,
            'id' => $id
        ];

        // Despachar el job para actualizar una receta específica
        SendMQ::handle('send_update_cousine_ingredient_queue','send_update_cousine_ingredient_exchange','cousine_key_update_ingredient', $payload);
        SendMQ::close();
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DeleteCousineJob::dispatch($id);
        return response()->json(['message' => 'Cousine deleted'], 200);
    }
}
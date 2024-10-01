<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('/cousine', \App\Http\Controllers\CousineController::class);
Route::put('/cousine-ingredient-updated/{cousine}', [\App\Http\Controllers\CousineController::class, 'updateWithIngredientsUpdated']);
Route::resource('/recipe', \App\Http\Controllers\RecipeController::class);
Route::resource('/grocery', \App\Http\Controllers\GroseryStoreController::class);
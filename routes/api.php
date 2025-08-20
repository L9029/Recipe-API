<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\TagController;
use App\Http\Controllers\Api\V1\RecipeController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Rutas de la API versión 1
Route::prefix('v1')->group(function () {
    // Categorias
    Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);

    // Etiquetas
    Route::apiResource('tags', TagController::class)->only(['index', 'show']);

    // Recetas
    Route::apiResource('recipes', RecipeController::class)->only(['index', 'show']);
});

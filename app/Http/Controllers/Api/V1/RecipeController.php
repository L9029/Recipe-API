<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use Illuminate\Http\Request;
use App\Http\Resources\Api\V1\RecipeResource;
use App\Http\Resources\Api\V1\RecipeCollection;

class RecipeController extends Controller
{
    /**
     * Retorna una lista de recetas paginadas.
     * 
     * @return \App\Http\Resources\Api\V1\RecipeCollection // Colección de recursos de las recetas
     */
    public function index()
    {
        $recipes = Recipe::latest()->paginate(10);

        return new RecipeCollection($recipes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Retorna una receta específica.
     * 
     * @param  \App\Models\Recipe  $recipe
     * @return \App\Http\Resources\Api\V1\RecipeResource // Recurso que formatea la respuesta de una receta
     */
    public function show(Recipe $recipe)
    {
        return new RecipeResource($recipe);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Recipe $recipe)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recipe $recipe)
    {
        //
    }
}

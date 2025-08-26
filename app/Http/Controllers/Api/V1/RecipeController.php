<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use Illuminate\Http\Request;
use App\Http\Resources\Api\V1\RecipeResource;
use App\Http\Resources\Api\V1\RecipeCollection;
use App\Http\Requests\Api\V1\StoreRecipeRequest;
use Symfony\Component\HttpFoundation\Response; // Para usar constantes de códigos de estado HTTP

class RecipeController extends Controller
{
    /**
     * Retorna una lista de recetas paginadas.
     * 
     * @return \App\Http\Resources\Api\V1\RecipeCollection // Colección de recursos de las recetas
     */
    public function index()
    {
        $recipes = Recipe::with("category", "tags", "user")->latest()->paginate(10);

        return new RecipeCollection($recipes);
    }

    /**
     * Almacena una nueva receta en la base de datos.
     * 
     * @param  \App\Http\Requests\Api\V1\StoreRecipeRequest  $validate_store_request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRecipeRequest $request)
    {
        $recipe = Recipe::create([
            'title' => $request->title,
            'description' => $request->description,
            'ingredients' => $request->ingredients,
            'instructions' => $request->instructions,
            'image' => $request->image,
            'category_id' => $request->category_id,
            'user_id' => $request->user_id,
        ]);

        $recipe->tags()->attach($request->tags); // Asocia las etiquetas a la receta

        return response()->json(new RecipeResource($recipe->load("category", "tags", "user")), Response::HTTP_CREATED);
    }

    /**
     * Retorna una receta específica.
     * 
     * @param  \App\Models\Recipe  $recipe
     * @return \App\Http\Resources\Api\V1\RecipeResource // Recurso que formatea la respuesta de una receta
     */
    public function show(Recipe $recipe)
    {
        return new RecipeResource($recipe->load("category", "tags", "user"));
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

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use Illuminate\Http\Request;
use App\Http\Resources\Api\V1\RecipeResource;
use App\Http\Resources\Api\V1\RecipeCollection;
use App\Http\Requests\Api\V1\StoreRecipeRequest;
use App\Http\Requests\Api\V1\UpdateRecipeRequest;
use Symfony\Component\HttpFoundation\Response; // Para usar constantes de códigos de estado HTTP
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RecipeController extends Controller
{
    use AuthorizesRequests;

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
     * @return \Illuminate\Http\JsonResponse // Respuesta JSON con la receta creada y el código de estado HTTP 201
     */
    public function store(StoreRecipeRequest $request)
    {
        $recipe = Recipe::create([
            'title' => $request->title,
            'description' => $request->description,
            'ingredients' => $request->ingredients,
            'instructions' => $request->instructions,
            'image' => $request->file("image")->store(), // Almacena la imagen y guarda la ruta
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
     * Actualiza una receta específica en la base de datos.
     * 
     * @param  \App\Http\Requests\Api\V1\UpdateRecipeRequest  $request
     * @param  \App\Models\Recipe  $recipe
     * @return \Illuminate\Http\JsonResponse // Respuesta JSON con la receta actualizada y el código de estado HTTP 200
     */
    public function update(UpdateRecipeRequest $request, Recipe $recipe)
    {
        // Verifica si el usuario autenticado tiene permiso para actualizar la receta
        $this->authorize('pass', $recipe);

        $recipe->update([
            "title" => $request->title,
            "description" => $request->description,
            "ingredients" => $request->ingredients,
            "instructions" => $request->instructions,
            'image' => $request->file("image")->store(), // Almacena la imagen y guarda la ruta
            "category_id" => $request->category_id,
            "user_id" => $request->user()->id,
        ]);

        $recipe->tags()->sync($request->tags); // Actualiza las etiquetas asociadas a la receta

        return response()->json(new RecipeResource($recipe->load("category", "tags", "user")), Response::HTTP_OK); // Código de estado HTTP 200 (OK)
    }

    /**
     * Elimina una receta específica de la base de datos.
     * 
     * @param  \App\Models\Recipe  $recipe
     * @return \Illuminate\Http\Response // Respuesta HTTP sin contenido (204)
     */
    public function destroy(Recipe $recipe)
    {
        // Verifica si el usuario autenticado tiene permiso para eliminar la receta
        $this->authorize('pass', $recipe);

        $recipe->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT); // Código de estado HTTP 204 (No Content)
    }
}

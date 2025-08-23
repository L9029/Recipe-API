<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Resources\Api\V1\CategoryCollection;
use App\Http\Resources\Api\V1\CategoryResource;

class CategoryController extends Controller
{
    /**
     * Retorna una lista de categorias paginadas.
     * 
     * @return \App\Http\Resources\Api\V1\CategoryCollection // Colección de recursos de las categorias
     */
    public function index()
    {
        $categories = Category::with("recipes")->latest()->paginate(10); // Se optienen las categorias mas recientes paginadas

        return new CategoryCollection($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Retorna una categoria específico.
     * 
     * @param  \App\Models\Category  $category
     * @return \App\Http\Resources\Api\V1\CategoryResource // Recurso que formatea la respuesta de category
     */
    public function show(Category $category)
    {
        return new CategoryResource($category->load("recipes"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}

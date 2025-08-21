<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Resources\Api\V1\TagCollection;
use App\Http\Resources\Api\V1\TagResource;

class TagController extends Controller
{
    /**
     * Retorna una lista de etiquetas paginadas.
     * 
     * @return \App\Http\Resources\Api\V1\TagCollection // Colección de recursos de las etiquetas
     */
    public function index()
    {
        $tags = Tag::latest()->paginate(10);

        return new TagCollection($tags);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Retorna una etiqueta específico.
     * 
     * @param  \App\Models\Tag  $tag
     * @return \App\Http\Resources\Api\V1\TagResource // Recurso que formatea la respuesta de tag
     */
    public function show(Tag $tag)
    {
        return new TagResource($tag);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tag $tag)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        //
    }
}

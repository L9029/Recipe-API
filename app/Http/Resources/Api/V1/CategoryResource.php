<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Formatea la respuesta de la categoría incluyendo sus recetas relacionadas
        return [
            "id" => $this->id,
            "type" => "category",
            "attributes" => [
                "name" => $this->name,
            ],
            "relationships" => [
                "recipes" => $this->recipes->map(function($recipe) {
                    return [
                        "id" => $recipe->id,
                        "title" => $recipe->title,
                        "description" => $recipe->description,
                        "ingredients" => $recipe->ingredients,
                        "instructions" => $recipe->instructions,
                        "image" => $recipe->image,
                        "category" => $recipe->category_id,
                        "tags" => $recipe->tags->map(function($tag) {
                            return [
                                "id" => $tag->id,
                                "name" => $tag->name,
                            ];
                        }),
                        "user" => [
                            "id" => $recipe->user->id,
                            "name" => $recipe->user->name,
                            "email" => $recipe->user->email,
                        ],
                    ];
                }),
            ]
        ];
    }
}

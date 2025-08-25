<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "type" => "tag",
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
                        "category" => [
                            "id" => $recipe->category->id,
                            "name" => $recipe->category->name,
                        ],
                        "tags" => $this->id,
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

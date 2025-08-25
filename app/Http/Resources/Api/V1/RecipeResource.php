<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecipeResource extends JsonResource
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
            "type" => "recipe",
            "attributes" => [
                "title" => $this->title,
                "description" => $this->description,
                "ingredients" => $this->ingredients,
                "instructions" => $this->instructions,
                "image" => $this->image,
                "category" => [
                    "id" => $this->category->id,
                    "name" => $this->category->name,
                ],
                "author" => [
                    "id" => $this->user->id,
                    "name" => $this->user->name,
                    "email" => $this->user->email,
                ],
                "tags" => $this->tags->map(function($tag) {
                    return [
                        "id" => $tag->id,
                        "name" => $tag->name,
                    ];
                }),
            ],
        ];
    }
}

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
            "title" => $this->title,
            "description" => $this->description,
            "ingredients" => $this->ingredients,
            "instructions" => $this->instructions,
            "image" => $this->image,
            "category" => $this->category->toArray(),
            "tags" => $this->tags->toArray(),
            "user" => $this->user->toArray(),
        ];
    }
}

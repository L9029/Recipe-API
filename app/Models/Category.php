<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    /**
     * Define la relación entre Category y Recipe.
     * Una categoría tiene muchas recetas.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Recipe>
     */
    public function recipes() {
        return $this->hasMany(Recipe::class);
    }
}

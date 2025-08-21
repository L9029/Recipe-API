<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /** @use HasFactory<\Database\Factories\TagFactory> */
    use HasFactory;

    /**
     * Define la relación entre Tag y Recipe.
     * Una etiqueta puede estar asociada a muchas recetas (relación muchos a muchos).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Recipe>
     */
    public function recipes() {
        return $this->belongsToMany(Recipe::class);
    }
}

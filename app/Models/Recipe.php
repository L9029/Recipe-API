<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $fillable = [
        'title',
        'description',
        'ingredients',
        'instructions',
        'image',
        'category_id',
        'user_id',
    ];

    /** @use HasFactory<\Database\Factories\RecipeFactory> */
    use HasFactory;

    /**
     * Define la relación entre Recipe y Category.
     * Una receta pertenece a una categoría.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Category>
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Define la relación entre Recipe y User.
     * Una receta pertenece a un usuario.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User>
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define la relación entre Recipe y Tag.
     * Una receta puede tener muchas etiquetas (relación muchos a muchos).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Tag>
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}

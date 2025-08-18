<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Recipe;
use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RecipeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Valida que una receta pertenece a una categoría específica.
     * 
     * @return void
     */
    public function test_recipe_belongs_to_a_category() : void
    {
        // Se crea una instancia de la categoría.
        $category = Category::factory()->create();

        // Crea una receta asociada a la categoría creada anteriormente.
        $recipe = Recipe::factory()->create(['category_id' => $category->id]);

        // Verifica que la relación 'category' del modelo Recipe devuelve una instancia de la clase Category.
        $this->assertInstanceOf(Category::class, $recipe->category);

        // Verifica que el ID de la categoría asociada a la receta coincide con el ID de la categoría creada.
        $this->assertEquals($category->id, $recipe->category->id);
    }

    /**
     * Valida que una receta pertenece a un usuario específico.
     * 
     * @return void
     */
    public function test_recipe_belongs_to_a_user() : void
    {
        // Crea una instancia de un usuario utilizando una fábrica.
        $user = User::factory()->create();

        // Crea una receta asociada al usuario creado anteriormente.
        $recipe = Recipe::factory()->create(['user_id' => $user->id]);

        // Verifica que la relación 'user' del modelo Recipe devuelve una instancia de la clase User.
        $this->assertInstanceOf(User::class, $recipe->user);

        // Verifica que el ID del usuario asociado a la receta coincide con el ID del usuario creado.
        $this->assertEquals($user->id, $recipe->user->id);
    }

    /**
     * Valida que una receta puede tener múltiples etiquetas.
     * 
     * @return void
     */
    public function test_recipe_belongs_to_many_tags() : void
    {
        // Crea una instancia de una receta utilizando una fábrica.
        $recipe = Recipe::factory()->create();

        // Crea tres instancias de etiquetas (tags) utilizando una fábrica.
        $tags = Tag::factory(3)->create();

        // Asocia las etiquetas creadas a la receta utilizando la relación 'tags'.
        $recipe->tags()->attach($tags);

        // Verifica que la cantidad de etiquetas asociadas a la receta es igual a 3.
        $this->assertCount(3, $recipe->tags);

        // Verifica que la primera etiqueta asociada a la receta es una instancia de la clase Tag.
        $this->assertInstanceOf(Tag::class, $recipe->tags->first());
    }
}

<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Recipe;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TagTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test que valida que una etiqueta tiene muchas recetas.
     * 
     * @return void
     */
    public function test_tag_has_many_recipes(): void
    {
        // Se crea una etiqueta.
        $tag = Tag::factory()->create();

        // Se crean varias recetas y se le asocia la etiqueta creada mediante la tabla pivote.
        $recipes = Recipe::factory(100)->create();
        $tag->recipes()->attach($recipes);

        // Verifica que la relación 'recipes' devuelve una colección.
        $this->assertInstanceOf(Collection::class, $tag->recipes);

        // Verifica que la cantidad de recetas asociadas a la etiqueta es igual.
        $this->assertCount(100, $tag->recipes);

        // Verifica que los elementos de la colección son instancias del modelo Recipe.
        $this->assertInstanceOf(Recipe::class, $tag->recipes->first());
    }
}

<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Recipe;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test que valida que una categoria tiene muchas recetas.
     * 
     * @return void
     */
    public function test_category_has_many_recipes(): void
    {
        // Se crea una categoría
        $category = Category::factory()->create();

        // Se crean recetas asociadas a la categoría creada.
        Recipe::factory(30)->create([
            'category_id' => $category->id
        ]);

        // Verifica que la relación 'recipes' devuelve una colección.
        $this->assertInstanceOf(Collection::class, $category->recipes);

        // Verifica que la cantidad de recetas asociadas a la categoría es igual a 3.
        $this->assertCount(30, $category->recipes);

        // Verifica que los elementos de la colección son instancias del modelo Recipe.
        $this->assertInstanceOf(Recipe::class, $category->recipes->first());
    }
}

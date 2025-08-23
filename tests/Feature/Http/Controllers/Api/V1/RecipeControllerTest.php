<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Recipe;

class RecipeControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test que valida que la api de recipes retorne correctamente las recetas paginadas en formato json.
     * 
     * @return void
     */
    public function test_api_recipe_index_with_data() : void 
    {
        // Se crean los registros necesarios para el test
        Recipe::factory(10)->create();

        // Realiza la solicitud GET a la ruta index de recipes y verifica que la respuesta venga en formato JSON con paginación y los datos correctos
        $this->getJson('/api/v1/recipes')
            ->assertStatus(200)
            ->assertJsonCount(10, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        "id",
                        "title",
                        "description",
                        "ingredients",
                        "instructions",
                        "image",
                        "category",
                        "tags",
                        "user",
                    ]
                ],
                'links' => [
                    'first',
                    'last',
                    'prev',
                    'next'
                ],
                'meta' => [
                    'current_page',
                    'from',
                    'last_page',
                    'path',
                    'per_page',
                    'to',
                    'total'
                ]
            ]);
    }

    /**
     * Test que valida que la api de recipes retorne correctamente las recetas paginadas en formato json aun cuando no haya data.
     * 
     * @return void
     */
    public function test_api_recipe_index_without_data() : void
    {
        // Realiza la solicitud GET a la ruta index de recipes y verifica que la respuesta venga en formato JSON con paginación y los datos vacios
        $this->getJson('/api/v1/recipes')
        ->assertStatus(200)
        ->assertJsonCount(0, 'data')
        ->assertJsonStructure([
            'data' => [],
            'links' => [
                'first',
                'last',
                'prev',
                'next'
            ],
            'meta' => [
                'current_page',
                'from',
                'last_page',
                'path',
                'per_page',
                'to',
                'total'
            ]
        ]);
    }

    /**
     * Test que valida que la api de recipes retorne correctamente una receta especifica en formato json.
     * 
     * @return void
     */
    public function test_api_recipe_show() : void
    {
        $recipe = Recipe::factory()->create();

        // Realiza la solicitud GET a la ruta show de una receta específica y verifica la respuesta
        $this->getJson('/api/v1/recipes/' . $recipe->id)
        ->assertStatus(200)
        ->assertJsonFragment([
            'id' => $recipe->id,
            "title" => $recipe->title,
            "description" => $recipe->description,
            "ingredients" => $recipe->ingredients,
            "instructions" => $recipe->instructions,
            "category" => $recipe->category->toArray(),
            "tags" => $recipe->tags->toArray(),
            "user" => $recipe->user->toArray(),
        ]);    
    }
}

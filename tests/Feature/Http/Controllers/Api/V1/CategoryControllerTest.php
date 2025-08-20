<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Category;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test que valida que la api de categories retorne correctamente las categorias paginadas en formato json.
     * 
     * @return void
     */
    public function test_api_category_index_with_data() : void 
    {
        // Se crean los registros necesarios para el test
        Category::factory(10)->create();

        // Realiza la solicitud GET a la ruta index de categories y verifica que la respuesta venga en formato JSON con paginación y los datos correctos
        $this->getJson('/api/v1/categories')
            ->assertStatus(200)
            ->assertJsonCount(10, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        "id",
                        "name",
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
     * Test que valida que la api de categories retorne correctamente las categorias paginadas en formato json aun cuando no haya data.
     * 
     * @return void
     */
    public function test_api_category_index_without_data() : void
    {
        // Realiza la solicitud GET a la ruta index de categories y verifica que la respuesta venga en formato JSON con paginación y los datos vacios
        $this->getJson('/api/v1/categories')
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
     * Test que valida que la api de categories retorne correctamente una categoria especifica en formato json.
     * 
     * @return void
     */
    public function test_api_category_show() : void
    {
        $category = Category::factory()->create();

        // Realiza la solicitud GET a la ruta show de una categoria específica y verifica la respuesta
        $this->getJson('/api/v1/categories/' . $category->id)
        ->assertStatus(200)
        ->assertJsonFragment([
            'id' => $category->id,
            'name' => $category->name,
        ]);    
    }
}

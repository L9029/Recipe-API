<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Tag;
use App\Models\User;

class TagControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test que valida que la api de tags retorne correctamente las etiquetas paginadas en formato json.
     * 
     * @return void
     */
    public function test_api_tag_index_with_data() : void 
    {
        // Usuario que simula la consulta a la API
        $user = User::factory()->create();

        // Se crean los registros necesarios para el test
        Tag::factory(10)->create();

        // Realiza la solicitud GET a la ruta index de tags y verifica que la respuesta venga en formato JSON con paginación y los datos correctos
        $this->actingAs($user, "sanctum")
            ->getJson('/api/v1/tags')
            ->assertStatus(200)
            ->assertJsonCount(10, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        "id",
                        "type",
                        "attributes" => [
                            "name",
                        ],
                        "relationships" => [
                            "recipes" => [
                                "*" => [
                                    "id",
                                    "title",
                                    "descripcion",
                                    "ingredients",
                                    "instructions",
                                    "image",
                                    "category" => [
                                        "id",
                                        "name",
                                    ],
                                    "tag",
                                    "user" => [
                                        "id",
                                        "name",
                                        "email",
                                    ],
                                ]
                            ]
                        ]
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
     * Test que valida que la api de tags retorne correctamente las etiquetas paginadas en formato json aun cuando no haya data.
     * 
     * @return void
     */
    public function test_api_tag_index_without_data() : void
    {
        // Usuario que simula la consulta a la API
        $user = User::factory()->create();

        // Realiza la solicitud GET a la ruta index de tags y verifica que la respuesta venga en formato JSON con paginación y los datos vacios
        $this->actingAs($user, "sanctum")
            ->getJson('/api/v1/tags')
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
     * Test que valida que la api de tags retorne correctamente una etiqueta especifica en formato json.
     * 
     * @return void
     */
    public function test_api_tag_show() : void
    {
        // Usuario que simula la consulta a la API
        $user = User::factory()->create();

        $tag = Tag::factory()->create();

        // Realiza la solicitud GET a la ruta show de una etiqueta específica y verifica la respuesta
        $this->actingAs($user, "sanctum")
            ->getJson('/api/v1/tags/' . $tag->id)
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $tag->id,
                "type" => "tag",
                "attributes" => [
                    'name' => $tag->name,
                ],
                "relationships" => [
                    'recipes' => $tag->recipes
                ]
            ]);
    }
}

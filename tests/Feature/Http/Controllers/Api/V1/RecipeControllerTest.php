<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Recipe;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;

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
                        "type",
                        "attributes" => [
                            "title",
                            "description",
                            "ingredients",
                            "instructions",
                            "image",
                            "category" => [
                                "id",
                                "name",
                            ],
                            "author" => [
                                "id",
                                "name",
                                "email",
                            ],
                            "tags"
                        ],
                    ],
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
            "type" => "recipe",
            "attributes" => [
                "title" => $recipe->title,
                "description" => $recipe->description,
                "ingredients" => $recipe->ingredients,
                "instructions" => $recipe->instructions,
                "image" => $recipe->image,
                "category" => [
                    "id" => $recipe->category->id,
                    "name" => $recipe->category->name,
                ],
                "author" => [
                    "id" => $recipe->user->id,
                    "name" => $recipe->user->name,
                    "email" => $recipe->user->email,
                ],
                "tags" => $recipe->tags->toArray(),
            ],
        ]);    
    }

    /**
     * Test que valida que la api pueda crear una receta correctamente
     * 
     * @return void
     */
    public function test_api_recipe_store() : void 
    {
        // Se crean los registros necesarios para el test
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $tags = Tag::factory(2)->create();

        // Datos de prueba para crear una receta
        $data = [
            "title" => "Receta de prueba",
            "description" => "Descripción de la receta de prueba",
            "ingredients" => "Ingredientes de la receta de prueba",
            "instructions" => "Instrucciones de la receta de prueba",
            "image" => "http://example.com/image.jpg",
            "category_id" => $category->id,
            "tags" => $tags->pluck("id")->toArray(),
            "user_id" => $user->id,
        ];

        // Se realiza la solicitud POST a la ruta store de recipes y se verifica que la receta se haya creado correctamente
        $this->postJson("api/v1/recipes", $data)
            ->assertStatus(201);
        
        // Por ultimo se verifica que la receta se haya creado en la base de datos
        $this->assertDatabaseHas("recipes", [
            "title" => $data["title"],
            "description" => $data["description"],
            "ingredients" => $data["ingredients"],
            "instructions" => $data["instructions"],
            "image" => $data["image"],
            "category_id" => $data["category_id"],
            "user_id" => $data["user_id"],
        ]);
    }

    /**
     * Test que valida que la api no pueda crear una receta sin los datos necesarios
     * 
     * @return void
     */
    public function test_api_recipe_validate_store() : void 
    {
        // Datos de prueba para crear una receta incompletos
        $data = [
            "title" => null,
            "ingredients" => "Ingredientes de la receta de prueba",
            "image" => "http://example.com/image.jpg",
            "category_id" => "prueba",
            "tags" => "prueba",
        ];

        $this->postJson("api/v1/recipes", $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'description', 'instructions', 'category_id', 'tags', "user_id"]);
    }

    /**
     * Test que valida que la api pueda actualizar una receta correctamente
     * 
     * @return void
     */
    public function test_api_recipe_update() : void 
    {
        // Usuario dueño de la receta
        $user = User::factory()->create();

        // Se crea una receta nueva que va a ser actualizada
        $recipe = Recipe::factory()->create([
            "user_id" => $user->id,
        ]);

        // Se crean registros nuevos para actualizar la receta
        $category = Category::factory()->create();
        $tags = Tag::factory(2)->create();

        // Datos de prueba para actualizar una receta
        $data = [
            "title" => "Receta de prueba",
            "description" => "Descripción de la receta de prueba",
            "ingredients" => "Ingredientes de la receta de prueba",
            "instructions" => "Instrucciones de la receta de prueba",
            "image" => "http://example.com/image.jpg",
            "category_id" => $category->id,
            "tags" => $tags->pluck("id")->toArray(),
        ];

        // Se realiza la solicitud PUT a la ruta update de recipes y se verifica que la receta se haya actualizado correctamente
        $this->actingAs($user)
            ->putJson("api/v1/recipes/{$recipe->id}", $data)
            ->assertStatus(200);
        
        // Por ultimo se verifica que la receta se haya actualizado en la base de datos
        $this->assertDatabaseHas("recipes", [
            "id" => $recipe->id,
            "title" => $data["title"],
            "description" => $data["description"],
            "ingredients" => $data["ingredients"],
            "instructions" => $data["instructions"],
            "image" => $data["image"],
            "category_id" => $data["category_id"],
            "user_id" => $user->id,
        ]);
    }

    /**
     * Test que valida que la api no pueda actualizar una receta sin los datos necesarios
     * 
     * @return void
     */
    public function test_api_recipe_validate_update() : void 
    {
        // Se crea una receta nueva que va a ser actualizada
        $recipe = Recipe::factory()->create();

        // Datos de prueba para actualizar una receta incompletos
        $data = [
            "title" => null,
            "ingredients" => "Ingredientes de la receta de prueba",
            "image" => "http://example.com/image.jpg",
            "category_id" => "prueba",
            "tags" => "prueba",
        ];

        $this->actingAs($user)
            ->putJson("api/v1/recipes/{$recipe->id}", $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'description', 'instructions', 'category_id', 'tags']);
    }

    /**
     * Test que valida que un usuario no pueda actualizar una receta que no le pertenece
     * 
     * @return void
     */
    public function test_api_recipe_update_policy() : void
    {
        // Se crea una receta nueva que va a ser actualizada
        $recipe = Recipe::factory()->create();

        // Se crean registros nuevos para actualizar la receta
        $user = User::factory()->create(); // Usuario diferente al creado en la receta
        $category = Category::factory()->create();
        $tags = Tag::factory(2)->create();

        // Datos de prueba para actualizar una receta
        $data = [
            "title" => "Receta de prueba",
            "description" => "Descripción de la receta de prueba",
            "ingredients" => "Ingredientes de la receta de prueba",
            "instructions" => "Instrucciones de la receta de prueba",
            "image" => "http://example.com/image.jpg",
            "category_id" => $category->id,
            "tags" => $tags->pluck("id")->toArray(),
            "user_id" => $user->id,
        ];

        // Se realiza la solicitud PUT a la ruta update de recipes y se verifica que la receta no se haya actualizado
        $this->actingAs($user)
            ->putJson("api/v1/recipes/{$recipe->id}", $data)
            ->assertStatus(403);

        // Por ultimo se verifica que la receta no se haya actualizado en la base de datos
        $this->assertDatabaseMissing("recipes", $data);
    }

    /**
     * Test que valida que la api pueda eliminar una receta correctamente
     * 
     * @return void
     */
    public function test_api_recipes_destroy() : void
    {
        // Usuario dueño de la receta
        $user = User::factory()->create();

        // Se crea una receta nueva que va a ser eliminada
        $recipe = Recipe::factory()->create([
            "user_id" => $user->id,
        ]);

        // Se realiza la solicitud DELETE a la ruta destroy de recipes y se verifica que la receta se haya eliminado correctamente
        $this->actingAs($user)
            ->deleteJson("api/v1/recipes/{$recipe->id}")
            ->assertStatus(204);

        // Por ultimo se verifica que la receta se haya eliminado en la base de datos
        $this->assertDatabaseMissing("recipes", $recipe->toArray());
    }

    /**
     * Test que valida que un usuario no pueda eliminar una receta que no le pertenece
     * 
     * @return void
     */
    public function test_api_recipe_destroy_policy() : void
    {
        // Se crea una receta nueva que va a ser eliminada
        $recipe = Recipe::factory()->create();

        // Se crea un usuario diferente al dueño de la receta
        $user = User::factory()->create();

        // Se realiza la solicitud DELETE a la ruta destroy de recipes y se verifica que la receta no se haya eliminado
        $this->actingAs($user)
            ->deleteJson("api/v1/recipes/{$recipe->id}")
            ->assertStatus(403);

        // Por ultimo se verifica que la receta no se haya eliminado en la base de datos
        $this->assertDatabaseHas("recipes", $recipe->toArray());
    }
}

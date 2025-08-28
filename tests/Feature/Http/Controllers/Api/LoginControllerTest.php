<?php

namespace Tests\Feature\Http\Controllers\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test que valida el inicio de sesión de la API con credenciales correctas.
     * 
     * @return void
     */
    public function test_api_login_validate_credentials(): void
    {
        // Usuario existente en la BD
        $user = User::factory()->create();

        // Datos erroneos
        $data = [
            "email" => "prueba@gmail.com",
            "password" => "12345678",
            "device_name" => null,
        ];

        // Simula una solicitud POST a la ruta de inicio de sesión de la API
        $this->postJson("api/v1/login", $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors(["email", "password", "device_name"]);
        
        // Verifica que no se haya creado un token de acceso personal
        $this->assertDatabaseMissing("personal_access_tokens", [
            "tokenable_id" => $user->id,
            "tokenable_type" => User::class,
        ]);
    }

    /**
     * Test que valida el inicio de sesión de la API con credenciales correctas.
     * 
     * @return void
     */
    public function test_api_login_with_correct_credentials() : void
    {
        // Usuario existente en la BD
        $user = User::factory()->create();

        // Datos Correctos
        $data = [
            "email" => $user->emial,
            "password" => $user->password,
            "device_name" => "android",
        ];

        // Simula una solicitud POST a la ruta de inicio de sesión de la API
        $this->postJson("api/v1/login", $data)
            ->assertStatus(201);
        
        // Verifica que se haya creado un token de acceso personal asociado al usuario
        $this->assertDatabaseHas("personal_access_tokens", [
            "tokenable_id" => $user->id,
            "tokenable_type" => User::class,
        ]);

        // Verifica que el usuario tenga al menos un token
        $this->assertNotNull($user->tokens()->first());
    }
}

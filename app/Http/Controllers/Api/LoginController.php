<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Maneja el inicio de sesión de un usuario y genera su token de acceso.
     *
     * Este método valida las credenciales del usuario (email y contraseña) y, si son correctas,
     * genera un token de acceso utilizando Laravel Sanctum. El token se asocia al dispositivo
     * especificado en la solicitud.
     *
     * @param  \App\Http\Requests\Api\LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request) 
    {
        
    }
}

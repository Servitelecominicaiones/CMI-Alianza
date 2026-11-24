<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Helpers\CryptoHelper;

class ApiAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $usuario = User::where('email', $request->email)->first();

        // Desencriptar la contraseña guardada y comparar
        $passwordGuardado = $usuario
            ? CryptoHelper::Enc('dec', $usuario->password)
            : null;

        if (!$usuario || $passwordGuardado !== $request->password) {
            return response()->json([
                'message' => 'Credenciales incorrectas.',
            ], 401);
        }

        if ($usuario->estado != 1) {
            return response()->json([
                'message' => 'Tu cuenta está inactiva. Contacta al administrador.',
            ], 403);
        }

        // Revocar tokens anteriores
        $usuario->tokens()->delete();

        $token = $usuario->createToken('api-token')->plainTextToken;

        return response()->json([
            'token'   => $token,
            'tipo'    => 'Bearer',
            'usuario' => [
                'id'     => $usuario->id,
                'nombre' => $usuario->nombre,
                'email'  => $usuario->email,
                'rol'    => $usuario->rol?->nombre,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente.',
        ]);
    }

    public function me(Request $request)
    {
        $usuario = $request->user();

        return response()->json([
            'id'     => $usuario->id,
            'nombre' => $usuario->nombre,
            'email'  => $usuario->email,
            'rol'    => $usuario->rol?->nombre,
        ]);
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Helpers\CryptoHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string'
        ]);

        // Buscar usuario activo
        $usuario = User::where('email', $request->email)
            ->where('estado', 1)
            ->first();

        if (!$usuario) {
            return back()->withErrors([
                'email' => 'Credenciales inválidas o usuario inactivo'
            ]);
        }

        // Desencriptar contraseña almacenada
        $passwordDesencriptada = CryptoHelper::Enc('dec', $usuario->password);

        
        // Comparar con la ingresada
        if ($passwordDesencriptada !== $request->password) {
            return back()->withErrors([
                'email' => 'Credenciales inválidas' 
            ]);
        }

        // Login manual
        Auth::login($usuario);
        $request->session()->regenerate();

        // Guardar datos clave en sesión
        session([
            'usuario_id'        => $usuario->id,
            'usuario_nombre'    => $usuario->nombre,
            'rol_id'            => $usuario->rol_id,
            'rol_nombre'        => $usuario->rol->nombre,
            'permisos_usuario'  => $usuario->rol
                ->permisos()
                ->pluck('permiso')
                ->toArray(),
        ]);

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

<?php

namespace App\Http\Controllers;

use App\Helpers\CryptoHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerfilController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();

        return view('perfil.index', compact('usuario'));
    }

    public function actualizarPassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[0-9].*[0-9]/',
                'regex:/[@$!%*?&#]/',
                'confirmed',
            ],
        ], [
            'current_password.required' => 'La contraseña actual es obligatoria.',
            'password.required' => 'La nueva contraseña es obligatoria.',
            'password.min' => 'La nueva contraseña debe tener mínimo 8 caracteres.',
            'password.regex' => 'La nueva contraseña debe contener al menos una mayúscula, dos números y un carácter especial.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ]);

        $usuario = Auth::user();

        $passwordActualDesencriptada = CryptoHelper::Enc('dec', $usuario->password);

        if ($passwordActualDesencriptada !== $request->current_password) {
            return back()->withErrors([
                'current_password' => 'La contraseña actual no es correcta.',
            ]);
        }

        $usuario->update([
            'password' => CryptoHelper::Enc('enc', $request->password),
            'password_changed_at' => now(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Contraseña actualizada correctamente',
            ]);
        }

        return redirect()
            ->route('perfil.index')
            ->with('success', 'Contraseña actualizada correctamente');
    }
}

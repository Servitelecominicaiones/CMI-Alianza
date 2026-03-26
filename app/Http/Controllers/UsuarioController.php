<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Helpers\CryptoHelper;
use function PHPUnit\Framework\returnArgument;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $usuarios = User::with('rol')->get();

        /** RESPUESTA API**/
        if($request->expectsJson()){
            return response()->json([
                'success' => true,
                'data' => $usuarios
            ]);
        }
        /** RESPUESTA WEB**/
        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $roles = Rol::all();
        return view('usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'   => 'required|string|max:255',
            'email'    => 'required|email|unique:usuarios,email',
            'rol_id'   => 'required|exists:roles,id',
            'estado'   => 'required|boolean',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[0-9].*[0-9]/',
                'regex:/[@$!%*?&#]/',
            ],
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            
            'email.required' => 'El correo es obligatorio.',
            'email.email'    => 'Ingrese un correo electrónico válido.',
            'email.unique'   => 'Este correo ya se encuentra registrado.',

            'password.required' => 'La contraseña es obligatoria.',
            'password.min'      => 'La contraseña debe tener mínimo 8 caracteres.',
            'password.regex'    => 'La contraseña debe contener al menos una mayúscula, dos números y un carácter especial.',
        ]);

        $usuario = User::create([
            'nombre'   => $request->nombre,
            'email'    => $request->email,
            'password' => CryptoHelper::Enc('enc',$request->password),
            'rol_id'   => $request->rol_id,
            'estado'   => $request->estado,
        ]);

        /** RESPUESTA API **/

        if ($request -> expectsJson()){
            return response()->json([
                'success' => true,
                'message' => 'usuario creado exitosamente',
                'data' => $usuario
            ],201);
        }

        /* RESPUESTA WEB */
        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario creado correctamente');
    }

    public function edit(User $usuario)
    {
        $roles = Rol::all();
        return view('usuarios.edit', compact('usuario', 'roles'));
    }

    public function update(Request $request, User $usuario)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email'  => 'required|email|unique:usuarios,email,' . $usuario->id,
            'rol_id' => 'required|exists:roles,id',
            'estado' => 'required|boolean',
            'password' => [
                'nullable',
                'string',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[0-9].*[0-9]/',
                'regex:/[@$!%*?&#]/',
            ],
        ], [
            'email.unique' => 'Este correo ya se encuentra registrado.',

            'password.min'   => 'La contraseña debe tener mínimo 8 caracteres.',
            'password.regex' => 'La contraseña debe contener al menos una mayúscula, dos números y un carácter especial.',
        ]);

        // Datos base
        $data = [
            'nombre' => $request->nombre,
            'email'  => $request->email,
            'rol_id' => $request->rol_id,
            'estado' => $request->estado,
        ];

        // SOLO si escribió contraseña
        if ($request->filled('password')) {
            $data['password'] = CryptoHelper::Enc('enc',$request->password);
        }

        $usuario->update($data);
        
        /** Respuesta api **/
        if ($request->expectsJson()){
            return response()->json([
                'success' => true,
                'message' => 'Usuario actualizado correctamente',
                'data'    => $usuario->fresh()
            ],201);
        }

        /** Respuesta Web **/
        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario actualizado correctamente');
    }


    public function destroy(User $usuario, Request $request)
    {
        $usuario->update(['estado' => 0]);

        /** Respuesta Api **/
        if ($request->expectsJson()){
            return response()->json([
                'success'=>true,
                'message'=>'Usuario inactivado exitosamente',
                'data' => [
                        $usuario -> nombre,
                        $usuario -> estado
                ]
            ]);
        }

        /** Respuesta Web **/
        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario inactivado');
    }
    
    public function inactivar($id)
    {
        $usuario = User::findOrFail($id);

        // Evitar auto-inactivarse
        if (auth()->id() === $usuario->id) {
            return redirect()
                ->route('usuarios.index')
                ->with('success', 'No puedes inactivar tu propio usuario.');
        }

        $usuario->update(['estado' => 0]);

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario inactivado correctamente');
    }

    public function activar($id)
    {
        $usuario = User::findOrFail($id);

        $usuario->update(['estado' => 1]);

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario activado correctamente');
    }


}

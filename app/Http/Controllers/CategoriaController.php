<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index(Request $request)
    {
        $categorias = Categoria::where('estado', 1)
            ->orderBy('nombre')
            ->get();

        /* RESPUESTA API */
        if($request->expectsJson()){
            return response()->json([
                'success'=>true,
                'data'=> $categorias
            ]);
        }

        /** RESPUESTA WEB */
        return view('categorias.index', compact('categorias'));
    }

    public function create()
    {
        return view('categorias.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:categorias,nombre'
        ]);

        $categoria = Categoria::create([
            'nombre' => $request->nombre,
            'estado' => 1
        ]);

        /* RESPUESTA API */
        if($request->expectsJson()){
            return response()->json([
                'success'=>true,
                'message'=> 'Categoria Creada Exitosamente',
                'data'=> $categoria
            ]);
        }

        /** RESPUESTA WEB */

        return redirect()
            ->route('categorias.index')
            ->with('success', 'Categoría creada correctamente');
    }

    public function edit(Categoria $categoria)
    {
        return view('categorias.edit', compact('categoria'));
    }

    public function update(Request $request, Categoria $categoria)
    {
        $request->validate([
            'nombre' => 'required|unique:categorias,nombre,' . $categoria->id
        ]);

        $categoria->update([
            'nombre' => $request->nombre
        ]);

        /* RESPUESTA API */
        if($request->expectsJson()){
            return response()->json([
                'success'=>true,
                'message'=>'Categoria editada exitosamente',
                'data'=> $categoria
            ]);
        }

        /** RESPUESTA WEB */

        return redirect()
            ->route('categorias.index')
            ->with('success', 'Categoría actualizada correctamente');
    }

    public function destroy(Categoria $categoria , Request $request)
    {
        $categoria->update([
            'estado' => 0
        ]);

        /* RESPUESTA API */
        if($request->expectsJson()){
            return response()->json([
                'success'=>true,
                'message'=>'Categoria incativada exitosamente',
                'data'=> [
                    $categoria->nombre,
                    $categoria->estado
                ]
            ]);
        }

        /** RESPUESTA WEB */
        return redirect()
            ->route('categorias.index')
            ->with('success', 'Categoría inactivada correctamente');
    }
}

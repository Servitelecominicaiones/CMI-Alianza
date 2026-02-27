<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::where('estado', 1)
            ->orderBy('nombre')
            ->get();

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

        Categoria::create([
            'nombre' => $request->nombre,
            'estado' => 1
        ]);

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

        return redirect()
            ->route('categorias.index')
            ->with('success', 'Categoría actualizada correctamente');
    }

    public function destroy(Categoria $categoria)
    {
        $categoria->update([
            'estado' => 0
        ]);

        return redirect()
            ->route('categorias.index')
            ->with('success', 'Categoría inactivada correctamente');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index()
    {
        $areas = Area::where('estado', 1)
            ->orderBy('nombre')
            ->get();

        return view('areas.index', compact('areas'));
    }

    public function create()
    {
        return view('areas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:areas,nombre'
        ]);

        Area::create([
            'nombre' => $request->nombre,
            'estado' => 1
        ]);

        return redirect()
            ->route('areas.index')
            ->with('success', 'Área creada correctamente');
    }

    public function edit(Area $area)
    {
        return view('areas.edit', compact('area'));
    }

    public function update(Request $request, Area $area)
    {
        $request->validate([
            'nombre' => 'required|unique:areas,nombre,' . $area->id
        ]);

        $area->update([
            'nombre' => $request->nombre
        ]);

        return redirect()
            ->route('areas.index')
            ->with('success', 'Área actualizada correctamente');
    }

    public function destroy(Area $area)
    {
        $area->update([
            'estado' => 0
        ]);

        return redirect()
            ->route('areas.index')
            ->with('success', 'Área inactivada correctamente');
    }
}

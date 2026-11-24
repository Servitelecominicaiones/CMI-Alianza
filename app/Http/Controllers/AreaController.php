<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index(Request $request)
    {
        $areas = Area::where('estado', 1)
            ->orderBy('nombre')
            ->get();

        /* RESPUESTA API */
        if($request->expectsJson()){
            return response()->json([
                'success'=>true,
                'data'=> $areas
            ]);
        }

        /** RESPUESTA WEB */
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

        $area = Area::create([
            'nombre' => $request->nombre,
            'estado' => 1
        ]);

        /* RESPUESTA API */
        if($request->expectsJson()){
            return response()->json([
                'success'=>true,
                'message'=> 'Area Creada exitosamente',
                'area' => $area
            ]);
        }

        /** RESPUESTA WEB */

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

        /* RESPUESTA API */
        if($request->expectsJson()){
            return response()->json([
                'success'=>true,
                'message'=> 'Area editada exitosamente',
                'data'=> $area ->fresh()
            ]);
        }

        /** RESPUESTA WEB */
        return redirect()
            ->route('areas.index')
            ->with('success', 'Área actualizada correctamente');
    }

    public function destroy(Area $area, Request $request)
    {
        $area->update([
            'estado' => 0
        ]);

        /* RESPUESTA API */
        if($request->expectsJson()){
            return response()->json([
                'success'=>true,
                'message' => 'Area Inactivada exitosamente',
                'data'=> [
                    $area->nombre,
                    $area->estado
                ]
            ]);
        }

        /** RESPUESTA WEB */
        return redirect()
            ->route('areas.index')
            ->with('success', 'Área inactivada correctamente');
    }
}

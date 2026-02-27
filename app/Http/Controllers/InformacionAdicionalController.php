<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colaborador;
use App\Models\InformacionAdicionalColaborador;

class InformacionAdicionalController extends Controller
{
    
    public function create(Colaborador $colaborador)
    {
        // Si ya tiene info adicional, redirige al edit para evitar duplicados
        if ($colaborador->informacionAdicional) {
            return redirect()->route('informacion_adicional.edit', $colaborador);
        }

        return view('informacionAdicional.create', compact('colaborador'));
    }

    public function store(Request $request, Colaborador $colaborador)
    {
        $validated = $request->validate([
            'cargo'               => 'nullable|string|max:100',
            'eps'                 => 'nullable|string|max:100',
            'fondo'               => 'nullable|string|max:100',
            'caja_compensacion'   => 'nullable|string|max:100',
            'salario_basico'      => 'nullable|numeric',
            'sub_transporte'      => 'nullable|numeric',
            'bono_servicio'       => 'nullable|numeric',
            'factor_prestacional' => 'nullable|numeric',
            'bono_salud_y_vivienda'=> 'nullable|numeric',
            'medios_transporte'   => 'nullable|string|max:100',
            'prima_riesgo'        => 'nullable|numeric',
            'auxilio_formacion'   => 'nullable|numeric',
            'comision_fija'       => 'nullable|numeric',
            'productividad_fija'  => 'nullable|numeric',
            'tiempo_extra_fijo'   => 'nullable|numeric',
            'bono_mercado'        => 'nullable|numeric',
            'auxilio_equipo'      => 'nullable|numeric',
            'recargo_nocturno'    => 'nullable|numeric',
            'trans_adicional'     => 'nullable|numeric',
            'fecha_inicial'       => 'nullable|date',
            'fecha_terminacion'   => 'nullable|date|after_or_equal:fecha_inicial',
        ]);

        $validated['id_colaborador'] = $colaborador->id_colaborador;

        InformacionAdicionalColaborador::create($validated);

        return redirect()->route('colaboradores.index')
            ->with('success', 'Información adicional creada correctamente.');
    }

    public function edit(Colaborador $colaborador)
    {
        $informacion = $colaborador->informacionAdicional;

        // Si no tiene info adicional, redirige al create
        if (!$informacion) {
            return redirect()->route('informacion_adicional.create', $colaborador);
        }

        return view('informacionAdicional.edit', compact('colaborador', 'informacion'));
    }

    public function update(Request $request, Colaborador $colaborador)
    {
        $informacion = $colaborador->informacionAdicional;

        $validated = $request->validate([
            'cargo'               => 'nullable|string|max:100',
            'eps'                 => 'nullable|string|max:100',
            'fondo'               => 'nullable|string|max:100',
            'caja_compensacion'   => 'nullable|string|max:100',
            'salario_basico'      => 'nullable|numeric',
            'sub_transporte'      => 'nullable|numeric',
            'bono_servicio'       => 'nullable|numeric',
            'factor_prestacional' => 'nullable|numeric',
            'bono_salud_y_vivienda'=> 'nullable|numeric',
            'medios_transporte'   => 'nullable|string|max:100',
            'prima_riesgo'        => 'nullable|numeric',
            'auxilio_formacion'   => 'nullable|numeric',
            'comision_fija'       => 'nullable|numeric',
            'productividad_fija'  => 'nullable|numeric',
            'tiempo_extra_fijo'   => 'nullable|numeric',
            'bono_mercado'        => 'nullable|numeric',
            'auxilio_equipo'      => 'nullable|numeric',
            'recargo_nocturno'    => 'nullable|numeric',
            'trans_adicional'     => 'nullable|numeric',
            'fecha_inicial'       => 'nullable|date',
            'fecha_terminacion'   => 'nullable|date|after_or_equal:fecha_inicial',
        ]);

        $informacion->update($validated);

        return redirect()->route('colaboradores.index')
            ->with('success', 'Información adicional actualizada correctamente.');
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colaborador;
use App\Models\InformacionAdicionalColaborador;
use App\Models\Empresa;
use App\Services\ContratoService;

class InformacionAdicionalController extends Controller
{
    
    protected $contratoService;

    public function __construct(ContratoService $contratoService)
    {   
        $this->contratoService = $contratoService;
    }

    public function create(Colaborador $colaborador)
    {
        $empresas = Empresa::where('estado' , 1)->get();

        // Si ya tiene info adicional, redirige al edit para evitar duplicados
        if ($colaborador->contratoActivo()) {
            return redirect()->route('colaboradores.detalle', $colaborador)->with('error','No se puede iniciar otro contraro mientras el colaborador tenga uno activo');        }

        return view('informacionAdicional.create', compact('colaborador', 'empresas'));
        }

    public function store(Request $request, Colaborador $colaborador)
    {
        $validated = $request->validate([
            'id_empresa'          => 'required|exists:empresa,id_empresa',
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
            'cajaUbica'           => 'nullable|numeric',
            'carpetaIn'           => 'nullable|numeric',
            'carpetaFin'          => 'nullable|numeric',
        ]);

        $this->contratoService->crearContratoConInformacion(
            $colaborador,
            $validated
        );

        return redirect()->route('colaboradores.index')
            ->with('success', 'Información adicional creada correctamente.');
    }

    public function edit(Colaborador $colaborador)
    {
        $contrato = $colaborador->contratos()
            ->where('estado',1)
            ->with(['informacionAdicional','empresa'])
            ->first();

        $informacion = $contrato->informacionAdicional;
        $empresas = Empresa::where('estado', 1)
            ->orWhere('id_empresa', $contrato->id_empresa)
            ->get();

        // Si no tiene info adicional, redirige al create
        if (!$informacion) {
            return redirect()->route('informacion_adicional.create', $colaborador);
        }

        return view('informacionAdicional.edit', compact('colaborador', 'informacion', 'empresas' , 'contrato'));
    }

    public function update(Request $request, Colaborador $colaborador)
    {

        $contrato = $colaborador->contratos()
            ->where('estado', 1)
            ->with('informacionAdicional')
            ->first();

        $id_colaborador = $colaborador->id_colaborador;

        $informacion = $contrato->informacionAdicional;

        $validated = $request->validate([
            'id_empresa'           => 'required|exists:empresa,id_empresa',
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
            'cajaUbica'           => 'nullable|numeric',
            'carpetaIn'           => 'nullable|numeric',
            'carpetaFin'          => 'nullable|numeric',
        ]);

        $contrato->update([
            'id_empresa' => $validated['id_empresa'],
            'inicio_contrato' => $validated['fecha_inicial'],
            'finalizacion_contrato' => $validated['fecha_terminacion']
        ]);

        $informacion->update($validated);

        return redirect()->route('colaboradores.detalle', $id_colaborador)
            ->with('success', 'Información adicional actualizada correctamente.');
    }

}

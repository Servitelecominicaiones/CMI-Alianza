<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContratoEmpresa;
use App\Models\InformacionAdicionalEmpresa;
use App\Models\Empresa;
use Illuminate\Support\Facades\DB;

class ContratoEmpresaController extends Controller
{
    public function create(Empresa $empresa)
    {
        if ($empresa->contratoEmpresaActivo()) {
            return redirect()
                ->route('empresas.detalle', $empresa)
                ->with('error', 'La empresa ya tiene un contrato activo.');
        }

        return view('contratosEmpresa.create', compact('empresa'));
    }

    public function store(Request $request, Empresa $empresa)
    {
        if ($empresa->contratoEmpresaActivo()) {
            return redirect()
                ->route('empresas.detalle', $empresa)
                ->with('error', 'La empresa ya tiene un contrato activo.');
        }

        $validated = $request->validate([
            'inicio_contrato'      => 'required|date',
            'finalizacion_contrato' => 'nullable|date|after_or_equal:inicio_contrato',
        ]);

        DB::transaction(function () use ($empresa, $validated) {

            $informacion = InformacionAdicionalEmpresa::create($validated);

            ContratoEmpresa::create([
                'id_empresa'                       => $empresa->id_empresa,
                'id_informacion_adicional_empresa' => $informacion->id_informacion_adicional_empresa,
                'estado'                           => 1
            ]);

            // Activar empresa si estaba inactiva
            $empresa->update(['estado' => 1]);
        });

        return redirect()
            ->route('empresas.detalle', $empresa)
            ->with('success', 'Contrato creado exitosamente.');
    }

     public function inactivarModal(Empresa $empresa)
    {
        $contrato = $empresa->contratosEmpresa()
            ->where('estado', 1)
            ->with('informacionAdicionalEmpresa')
            ->first();

        if (!$contrato) {
            return response()->json(['error' => 'No hay contrato activo'], 404);
        }

        return view('contratosEmpresa.modal-inactivar', compact('empresa', 'contrato'));
    }


    public function inactivar(Request $request, Empresa $empresa)
    {
        $contratoEmpresa = $empresa->contratosEmpresa()
            ->where('estado',1)
            ->first();

        if (!$contratoEmpresa) {
            return redirect()
                ->route('empresas.index')
                ->with('error', 'Esta empresa no tiene un contrato activo.');
        }

        $request->validate([
            'motivo' => 'required|string|max:500',
        ]);

        $contratoEmpresa->update([
            'estado'              => 0,
            'motivo_inactivacion' => $request->motivo,
        ]);

        $empresa->update(['estado' => 0]);

        return redirect()
            ->route('empresas.index')
            ->with('success', 'Empresa y contrato inactivados exitosamente.');
    }

    public function edit(Empresa $empresa)
    {
        $contrato = $empresa->contratosEmpresa()
            ->where('estado', 1)
            ->with('informacionAdicionalEmpresa')
            ->first();

        if (!$contrato) {
            return redirect()
                ->route('contratosEmpresa.create', $empresa);
        }

        $informacion = $contrato->informacionAdicionalEmpresa;

        return view('contratosEmpresa.edit', compact('empresa', 'contrato', 'informacion'));
    }

    public function update(Request $request, Empresa $empresa)
    {
        $contrato = $empresa->contratosEmpresa()
            ->where('estado', 1)
            ->with('informacionAdicionalEmpresa')
            ->first();

        if (!$contrato) {
            return redirect()
                ->route('contratosEmpresa.create', $empresa);
        }

        $validated = $request->validate([
            'inicio_contrato'      => 'required|date',
            'finalizacion_contrato' => 'nullable|date|after_or_equal:inicio_contrato',
        ]);

        $contrato->informacionAdicionalEmpresa->update($validated);

        return redirect()
            ->route('empresas.detalle', $empresa);
    }

    public function verInfo(ContratoEmpresa $contratoEmpresa)
    {
        $contratoEmpresa->load(['informacionAdicionalEmpresa', 'empresa']);
        return view('contratosEmpresa.ver-info', compact('contratoEmpresa'));
    }

    public function documentos(ContratoEmpresa $contratoEmpresa)
    {
        $contratoEmpresa->load(['empresa', 'informacionAdicionalEmpresa']);
    
        $documentos = $contratoEmpresa->documentos()
            ->with(['categoria', 'area', 'usuario'])
            ->orderByDesc('created_at')
            ->get();
    
        return view('contratosEmpresa.documentos', compact('contratoEmpresa', 'documentos'));
    }
    
}

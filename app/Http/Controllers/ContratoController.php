<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contrato;
use App\Models\Colaborador;
use Carbon\Carbon;

class ContratoController extends Controller
{

    public function inactivarModal(Colaborador $colaborador)
    {
        $contrato = $colaborador->contratos()
            ->where('estado', 1)
            ->with('informacionAdicional')
            ->first();

        if (!$contrato) {
            return response()->json(['error' => 'No hay contrato activo'], 404);
        }

        return view('contratos.modal-inactivar', compact('colaborador', 'contrato'));
    }
    public function inactivar(Request $request, Colaborador $colaborador)
    {
        $contrato = $colaborador->contratos()
            ->where('estado', 1)
            ->first();

        if (!$contrato) {
            return redirect()
                ->route('colaboradores.index')
                ->with('error', 'Este colaborador no tiene un contrato activo.');
        }

        $request->validate([
            'motivo' => 'required|string|max:500',
        ]);

        $contrato->update([
            'estado' => 0,
            'motivo_inactivacion' => $request->motivo,
            'finalizacion_contrato' => Carbon::now()
        ]);

        // Inactivar colaborador también
        $colaborador->update(['estado' => 0]);

        return redirect()
            ->route('colaboradores.index')
            ->with('success', 'Contrato inactivado exitosamente.');
    }

    public function verInfo(Contrato $contrato)
    {
        $contrato->load(['informacionAdicional', 'empresa', 'colaborador']);

        return view('contratos.ver-info', compact('contrato'));
    }

    public function documentos(Contrato $contrato){
        $documentos = $contrato->documentos()
                    ->with(['categoria','area','usuario'])
                    ->orderBy('created_at')
                    ->get();
        return view('contratos.documentos', compact('documentos','contrato'));
    }
}


<?php

namespace App\Http\Controllers;

use App\Models\HistorialDocumento;
use Illuminate\Http\Request;

class HistorialDocumentoController extends Controller
{
    /* ================= INDEX ================= */

    public function index(Request $request)
    {
        $query = HistorialDocumento::with(['documento', 'usuario'])
            ->orderByDesc('id');

        if ($request->documento_id) {
            $query->where('documento_id', $request->documento_id);
        }

        if ($request->usuario_id) {
            $query->where('usuario_id', $request->usuario_id);
        }

        if ($request->accion) {
            $query->where('accion', $request->accion);
        }

        if ($request->fecha_inicio && $request->fecha_fin) {
            $query->whereBetween('created_at', [
                $request->fecha_inicio,
                $request->fecha_fin
            ]);
        }

        $historial = $query->paginate(20)->withQueryString();

        return view('historial_documentos.index', compact('historial'));
    }

    /* ================= SHOW ================= */

    public function show(HistorialDocumento $historialDocumento)
    {
        return view('historial_documentos.show', compact('historialDocumento'));
    }
}

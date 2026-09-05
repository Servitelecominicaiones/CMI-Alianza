<?php

namespace App\Http\Controllers;

use App\Models\HistorialDocumento;
use App\Models\User;
use App\Exports\HistorialDocumentosExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class HistorialDocumentoController extends Controller
{
    /* ================= INDEX ================= */

    public function index()
    {
        // Ajusta 'nombre' al campo real de tu modelo User si es distinto
        $usuarios = User::orderBy('nombre')->get();
        $acciones = HistorialDocumento::select('accion')->distinct()->pluck('accion');

        return view('historial_documentos.index', compact('usuarios', 'acciones'));
    }

    /* ================= DATA (AJAX server-side de DataTables) ================= */

    public function data(Request $request)
    {
        $query = HistorialDocumento::with(['documento', 'usuario']);
        $this->aplicarFiltros($query, $request);

        $recordsTotal = HistorialDocumento::count();
        $recordsFiltered = (clone $query)->count();

        // Búsqueda global (caja de búsqueda de DataTables)
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre_documento', 'like', "%{$search}%")
                  ->orWhere('accion', 'like', "%{$search}%")
                  ->orWhereHas('usuario', function ($uq) use ($search) {
                      $uq->where('nombre', 'like', "%{$search}%");
                  });
            });
            $recordsFiltered = (clone $query)->count();
        }

        // Ordenamiento por columna (debe coincidir con el orden de <th> en la vista)
        $columnas = ['created_at', 'nombre_documento', 'accion', 'usuario_id', 'ruta_anterior', 'ruta_nueva', null];

        $orderColIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'desc');
        $orderCol = $columnas[$orderColIndex] ?? 'created_at';

        $query->orderBy($orderCol, $orderDir);

        // Paginación (la maneja DataTables mandando start/length)
        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 20);

        $registros = $query->skip($start)->take($length)->get();

        $data = $registros->map(function ($h) {
            return [
                'fecha'         => $h->created_at ? $h->created_at->format('Y-m-d H:i') : '—',
                'documento'     => $h->nombre_documento ?? 'Documento eliminado',
                'accion'        => $h->accion,
                'usuario'       => $h->usuario->nombre ?? 'Sistema',
                'ruta_anterior' => $h->ruta_anterior ?? '-',
                'ruta_nueva'    => $h->ruta_nueva ?? '-',
                'ver'           => route('historial_documentos.show', $h->id),
            ];
        });

        return response()->json([
            'draw'            => intval($request->input('draw')),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data,
        ]);
    }

    /* ================= EXPORTAR TODO (respetando filtros) ================= */

    public function exportExcel(Request $request)
    {
        $query = HistorialDocumento::with(['documento', 'usuario']);
        $this->aplicarFiltros($query, $request);
        $query->orderByDesc('created_at');

        return Excel::download(
            new HistorialDocumentosExport($query),
            'historial_documentos_' . now()->format('Y-m-d_His') . '.xlsx'
        );
    }

    /* ================= Filtros compartidos (index AJAX + export) ================= */

    private function aplicarFiltros($query, Request $request)
    {
        if ($request->filled('documento_id')) {
            $query->where('documento_id', $request->documento_id);
        }

        if ($request->filled('usuario_id')) {
            $query->where('usuario_id', $request->usuario_id);
        }

        if ($request->filled('accion')) {
            $query->where('accion', $request->accion);
        }

        if ($request->fecha_inicio && $request->fecha_fin) {
            $query->whereBetween('created_at', [
                $request->fecha_inicio,
                $request->fecha_fin
            ]);
        }
    }

    /* ================= SHOW ================= */

    public function show(HistorialDocumento $historialDocumento)
    {
        return view('historial_documentos.show', compact('historialDocumento'));
    }
}
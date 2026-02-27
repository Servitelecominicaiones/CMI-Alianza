<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\Area;
use App\Models\Categoria;
use App\Models\Empresa;
use App\Models\Colaborador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\HistorialDocumento;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class DocumentoController extends Controller
{
    public function index(Request $request)
    {
        $query = Documento::with(['area', 'categoria', 'usuario', 'owner']);

        /* ================= ESTADO ================= */
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        } else {
            $query->where('estado', 1); // por defecto activos
        }

        /* ================= FILTROS ================= */

        if ($request->filled('area_id')) {
            $query->where('area_id', $request->area_id);
        }

        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        if ($request->filled('anio')) {
            $query->where('anio', $request->anio);
        }

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('nombre_original', 'like', '%' . $request->q . '%')
                ->orWhere('descripcion', 'like', '%' . $request->q . '%');
            });
        }

        /* ================= PAGINACIÓN ================= */

        $documentos = $query
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        /* ================= AÑOS DISPONIBLES ================= */

        $anios = Documento::select('anio')
            ->distinct()
            ->orderByDesc('anio')
            ->pluck('anio');

        /* ================= AJAX ================= */

        if ($request->ajax()) {
            return view('documentos.partials.cards', compact('documentos'));
        }

        /* ================= VISTA ================= */

        return view('documentos.index', [
            'documentos' => $documentos,
            'areas' => Area::where('estado', 1)->orderBy('nombre')->get(),
            'categorias' => Categoria::where('estado', 1)->orderBy('nombre')->get(),
            'anios' => $anios,
        ]);
    }



    /* ================= PREVIEW SEGURO ================= */

    public function preview($id)
    {
        $documento = Documento::findOrFail($id);

        if (empty($documento->ruta_completa)) {
            abort(404, 'Ruta no registrada en BD');
        }

        $ruta = public_path($documento->ruta_completa);

        if (!file_exists($ruta)) {
            abort(404, 'Archivo no encontrado');
        }

        return response()->file($ruta, [
            'Content-Type' => mime_content_type($ruta),
            'Content-Disposition' => 'inline'
        ]);
    }

    // public function previewUrl()
    // {
    //     return route('documentos.preview', $this->id);
    // }


    /* ================= CREAR ================= */

    public function create()
    {
        return view('documentos.create', [
            'areas' => Area::where('estado', 1)->get(),
            'categorias' => Categoria::where('estado', 1)->get(),
            'empresas' => Empresa::where('estado',1)->get(),
            'colaboradores' => Colaborador::where('estado',1)->get()
        ]);
    }

    public function store(Request $request)
    {
        /* ===== VALIDACIÓN ===== */
        $request->validate([
            'archivo'       => 'required|file|max:20480', // 20MB
            'categoria_id'  => 'required|exists:categorias,id',
            'area_id'       => 'required|exists:areas,id',
            'descripcion'   => 'nullable|string',
            'tipo_propietario' => 'nullable|in:empresa,colaborador',
            'empresa_id' => 'required_if:tipo_propietario,empresa|nullable|exists:empresa,id_empresa',
            'colaborador_id' => 'required_if:tipo_propietario,colaborador|nullable|exists:colaborador,id_colaborador'
        ]);

        /* ===== ARCHIVO ===== */
        $file = $request->file('archivo');

        /* ===== DATOS ANTES DE MOVER ===== */
        $tamanio         = $file->getSize();
        $nombreOriginal  = $file->getClientOriginalName();
        $extension       = $file->getClientOriginalExtension();

        /* ===== RELACIONES ===== */
        $categoria = Categoria::findOrFail($request->categoria_id);
        $area      = Area::findOrFail($request->area_id);
        $anio      = date('Y');

        /* ===== NOMBRE ÚNICO ===== */
        $hashNombre = Str::uuid() . '.' . $extension;

        /* ===== RUTA FÍSICA ===== */
        $rutaFisica = public_path(
            'bodega_documental/' .
            Str::slug($categoria->nombre) . '/' .
            Str::slug($area->nombre) . '/' .
            $anio
        );

        if (!file_exists($rutaFisica)) {
            mkdir($rutaFisica, 0775, true);
        }

        /* ===== MOVER ARCHIVO ===== */
        $file->move($rutaFisica, $hashNombre);

        /* ===== RUTA PARA BD ===== */
        $rutaBD = 'bodega_documental/' .
            Str::slug($categoria->nombre) . '/' .
            Str::slug($area->nombre) . '/' .
            $anio . '/' .
            $hashNombre;
        
        /* ===== DETERMINAR PROPIETARIO DEL DOCUMENTO ===== */
        $ownerType = null;
        $ownerId = null;

        if ($request->filled('tipo_propietario')) {
            if ($request->tipo_propietario === 'empresa' && $request->filled('empresa_id')) {
                $ownerType = 'empresa';
                $ownerId = $request->empresa_id;
            } elseif ($request->tipo_propietario === 'colaborador' && $request->filled('colaborador_id')) {
                $ownerType = 'colaborador';
                $ownerId = $request->colaborador_id;
            }
        }

        /* ===== GUARDAR DOCUMENTO ===== */
        $documento = Documento::create([
            'nombre_original'    => $nombreOriginal,
            'nombre_archivo'     => $hashNombre,
            'ruta_completa'      => $rutaBD,
            'categoria_id'       => $categoria->id,
            'area_id'            => $area->id,
            'anio'               => $anio,
            'extension'          => $extension,
            'tamanio'            => $tamanio,
            'descripcion'        => $request->descripcion,
            'usuario_carga_id'   => auth()->id(),
            'estado'             => 1,
            'owner_type'         => $ownerType,
            'owner_id'           => $ownerId
        ]);

        /* ===== HISTORIAL ===== */
        HistorialDocumento::create([
            'documento_id' => $documento->id,
            'usuario_id'   => auth()->id(),
            'accion'       => 'CREAR',
            'ruta_anterior'=> null,
            'ruta_nueva'   => $rutaBD
        ]);

        /* ===== RESPUESTA ===== */
        return redirect()
            ->route('documentos.index')
            ->with('success', 'Documento cargado correctamente');
    }


    /* ================= INACTIVAR ================= */

    public function destroy($id)
    {
        $doc = Documento::findOrFail($id);
        $doc->estado = 0;
        $doc->save();

        /* ===== HISTORIAL ===== */
        HistorialDocumento::create([
            'documento_id' => $doc->id,
            'usuario_id'   => auth()->id(),
            'accion'       => 'INACTIVAR',
            'ruta_anterior'=> $doc->ruta_completa,
            'ruta_nueva'   => null
        ]);

        return redirect()
            ->route('documentos.index')
            ->with( 'success','Documento Inactivado exitosamente');
        
    }

    /* ================= ELIMINACIÓN DEFINITIVA ================= */

    public function eliminarDefinitivo($id)
    {
        $doc = Documento::findOrFail($id);

        if (!$doc->puedeEliminarse()) {
            abort(403, 'Aún no puede eliminarse');
        }

        Storage::delete($doc->ruta_completa);
        $doc->delete();

        return back();
    }

    /* ================= DUPLICAR ================= */

    public function duplicar($id)
    {
        $doc = Documento::findOrFail($id);

        $nuevoNombre = Str::uuid() . '.' . $doc->extension;
        $nuevaRuta = str_replace($doc->nombre_archivo, $nuevoNombre, $doc->ruta_completa);

        Storage::copy($doc->ruta_completa, $nuevaRuta);

        Documento::create([
            ...$doc->only([
                'nombre_original','area_id','categoria_id',
                'anio','extension','tamanio','descripcion'
            ]),
            'nombre_archivo' => $nuevoNombre,
            'ruta_completa' => $nuevaRuta,
            'usuario_carga_id' => auth()->id()
        ]);

        return back();
    }

    /* ================= Editar ================= */
    public function edit(Documento $documento)
    {
        return view('documentos.edit', [
            'documento' => $documento,
            'areas' => Area::where('estado', 1)->get(),
            'categorias' => Categoria::where('estado', 1)->get(),
            'empresas' => Empresa::where('estado',1)->get(),
            'colaboradores' => Colaborador::where('estado',1)->get()
        ]);
    }

    public function update(Request $request, Documento $documento)
    {
        $request->validate([
            'area_id' => 'required',
            'categoria_id' => 'required',
            'descripcion' => 'nullable|string',
            'tipo_propietario' => 'nullable|in:empresa,colaborador',
            'empresa_id' => 'required_if:tipo_propietario,empresa|nullable|exists:empresa,id_empresa',
            'colaborador_id' => 'required_if:tipo_propietario,colaborador|nullable|exists:colaborador,id_colaborador'
        ]);

        DB::beginTransaction();

        try {
            $categoriaNueva = Categoria::findOrFail($request->categoria_id);
            $areaNueva = Area::findOrFail($request->area_id);
            $anio = $documento->anio;

            // === RUTA ACTUAL ===
            $rutaActual = public_path($documento->ruta_completa);

            // === NUEVA RUTA ===
            $nuevaRutaFisica = public_path(
                'bodega_documental/' .
                Str::slug($categoriaNueva->nombre) . '/' .
                Str::slug($areaNueva->nombre) . '/' .
                $anio
            );

            // Crear carpetas si no existen
            if (!file_exists($nuevaRutaFisica)) {
                mkdir($nuevaRutaFisica, 0775, true);
            }

            $nuevaRutaBD = 'bodega_documental/' .
                Str::slug($categoriaNueva->nombre) . '/' .
                Str::slug($areaNueva->nombre) . '/' .
                $anio . '/' .
                $documento->nombre_archivo;

            // === MOVER ARCHIVO SI CAMBIÓ LA RUTA ===
            if ($documento->ruta_completa !== $nuevaRutaBD) {

                if (!file_exists($rutaActual)) {
                    throw new \Exception('Archivo físico no encontrado');
                }

                rename(
                    $rutaActual,
                    public_path($nuevaRutaBD)
                );
            }

            // === Determinar Propietario del archivo ===
            $ownerType = null;
            $ownerId = null;

            if ($request->filled('tipo_propietario')) 
            {
                if ($request->tipo_propietario === 'empresa' && $request->filled('empresa_id')) {
                    $ownerType = 'empresa';
                    $ownerId = $request->empresa_id;
                } elseif ($request->tipo_propietario === 'colaborador' && $request->filled('colaborador_id')) {
                    $ownerType = 'colaborador';
                    $ownerId = $request->colaborador_id;
                }
            }

            // === ACTUALIZAR DOCUMENTO ===
            $documento->update([
                'categoria_id' => $categoriaNueva->id,
                'area_id' => $areaNueva->id,
                'descripcion' => $request->descripcion,
                'ruta_completa' => $nuevaRutaBD,
                'owner_type' => $ownerType,
                'owner_id' => $ownerId
            ]);

            // === REGISTRAR HISTORIAL ===
            HistorialDocumento::create([
                'documento_id' => $documento->id,
                'usuario_id' => auth()->id(),
                'accion' => 'EDITAR',
                'ruta_anterior' => $documento->getOriginal('ruta_completa'),
                'ruta_nueva' => $nuevaRutaBD
            ]);

            DB::commit();

            return redirect()
                ->route('documentos.index')
                ->with('success', 'Documento actualizado correctamente');

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

}

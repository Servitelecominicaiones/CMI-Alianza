<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\Area;
use App\Models\Categoria;
use App\Models\Empresa;
use App\Models\Colaborador;
use App\Models\Contrato;
use App\Models\ContratoEmpresa;
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

        /* ================= RESPUESTA API ================= */
        if($request->expectsJson()){
            return response() ->json([
                'success' => true,
                'data'=> $documentos
            ]);
        }

        /* ================= VISTA ================= */

        return view('documentos.index', [
            'documentos' => $documentos,
            'areas' => Area::where('estado', 1)->orderBy('nombre')->get(),
            'categorias' => Categoria::where('estado', 1)->orderBy('nombre')->get(),
            'anios' => $anios,
        ]);
    }



    /* ================= PAPELERA ================= */

    public function papelera(Request $request)
    {
        $query = Documento::with(['area', 'categoria', 'usuario', 'owner'])
            ->where('estado', 0);

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

        if ($request->filled('tipo_propietario')) {
            $query->where('owner_type', $request->tipo_propietario);
        }

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('nombre_original', 'like', '%' . $request->q . '%')
                ->orWhere('descripcion', 'like', '%' . $request->q . '%');
            });
        }

        /* ================= PAGINACIÓN ================= */

        $documentos = $query
            ->orderByDesc('updated_at')
            ->paginate(20)
            ->withQueryString();

        /* ================= AÑOS DISPONIBLES ================= */

        $anios = Documento::where('estado', 0)
            ->select('anio')
            ->distinct()
            ->orderByDesc('anio')
            ->pluck('anio');

        /* ================= VISTA ================= */

        return view('documentos.papelera', [
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
            #'colaboradores' => Colaborador::where('estado',1)->get(),
            #'contratos' => Contrato::where('estado',1) -> with(['colaborador','empresa','informacionAdicional'])->get(),
            'contratosEmpresa' => ContratoEmpresa::where('estado',1)->with(['empresa','informacionAdicionalEmpresa'])->get()
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
            'tipo_propietario' => 'nullable|in:empresa,colaborador,contrato,contratoEmpresa|',
            'empresa_id' => 'required_if:tipo_propietario,empresa|nullable|exists:empresa,id_empresa',
            'colaborador_id' => 'required_if:tipo_propietario,colaborador|nullable|exists:colaborador,id_colaborador',
            'contrato_id' => 'required_if:tipo_propietario,contrato|nullable|exists:contratos,id_contrato',
            'contrato_empresa_id' => 'required_if:tipo_propietario,contratoEmpresa|nullable|exists:contrato_empresa,id_contrato_empresa'
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

         /* ===== DETERMINAR PROPIETARIO DEL DOCUMENTO ===== */
        $ownerType = null;
        $ownerId = null;

        if ($request->filled('tipo_propietario')) {
            if ($request->tipo_propietario === 'empresa' && $request->filled('empresa_id')) {
                $ownerType = 'empresa';
                $ownerId = $request->empresa_id;
                $empresa = Empresa::findOrFail($ownerId);
                $identificador = $empresa -> nombre_empresa;
            } elseif ($request->tipo_propietario === 'colaborador' && $request->filled('colaborador_id')) {
                $ownerType = 'colaborador';
                $ownerId = $request->colaborador_id;
                $colaborador = Colaborador::findOrFail($ownerId);
                $identificador = $colaborador->numero_identificacion;
            }elseif ($request-> tipo_propietario === 'contrato' && $request->filled('contrato_id')){
                $ownerType = 'contrato';
                $ownerId = $request->contrato_id;
                $contrato = Contrato::with('colaborador')->findOrFail($ownerId);
                $identificador = $contrato->colaborador->numero_identificacion . 
                '-' . $contrato->informacionAdicional->cargo . '-' . $contrato->informacionAdicional->fecha_inicial
                .'-'. $contrato->informacionAdicional->fecha_terminacion;

            }elseif ($request->tipo_propietario === 'contratoEmpresa' && $request->filled('contrato_empresa_id')) {
                $ownerType = 'contratoEmpresa';
                $ownerId   = $request->contrato_empresa_id;
                $contratoEmpresa = ContratoEmpresa::with('empresa')->findOrFail($ownerId);
                $identificador   = $contratoEmpresa->empresa->nombre_empresa . '-' .
                $contratoEmpresa->informacionAdicionalEmpresa->inicio_contrato . '-' .
                $contratoEmpresa->informacionAdicionalEmpresa->finalizacion_contrato;
            }
        }


        /* ===== RUTA FÍSICA ===== */
        $rutaFisica = public_path(
            'bodega_documental/' .
            Str::slug($ownerType). '/' .
            Str::slug($identificador). '/' .
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
            Str::slug($ownerType). '/' .
            Str::slug($identificador). '/' .
            Str::slug($categoria->nombre) . '/' .
            Str::slug($area->nombre) . '/' .
            $anio . '/' .
            $hashNombre;

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
            'usuario_carga_id'   => auth()->id() ?? 1, //asignar usuario de api
            'estado'             => 1,
            'owner_type'         => $ownerType,
            'owner_id'           => $ownerId
        ]);

        /* ===== HISTORIAL ===== */
        HistorialDocumento::create([
            'documento_id' => $documento->id,
            'usuario_id'   => auth()->id() ?? 1,//asignar usuario de api
            'nombre_documento' => $documento->nombre_original,
            'accion'       => 'CREAR',
            'ruta_anterior'=> null,
            'ruta_nueva'   => $rutaBD
        ]);

        /* RESPUESTA API */
        if ($request -> expectsJson()){
            return response()->json([
                'success'=>true,
                'message'=>'Documento creado exitosamente',
                'data'=> $documento
            ]);
        }

        /* ===== RESPUESTA ===== */
        
        //Si viene desde el detalle del colaborador, redirigir de vuelta
        if ($request->filled('redirect_colaborador')) {
            return redirect()
                ->route('colaboradores.detalle', $request->redirect_colaborador)
                ->with('success', 'Documento cargado correctamente');
        }elseif ($request->filled('redirect_empresa')) {
            return redirect()
            ->route('empresas.detalle', $request->redirect_empresa)
            ->with('success', 'Documento cargado correctamente');
        }

        return redirect()
            ->route('documentos.index')
            ->with('success', 'Documento cargado correctamente');
    
    }


    /* ================= INACTIVAR ================= */

    public function destroy($id, Request $request) 
    {
        $doc = Documento::findOrFail($id);
        $doc->estado = 0;
        $doc->save();

        /* ===== HISTORIAL ===== */
        HistorialDocumento::create([
            'documento_id' => $doc->id,
            'usuario_id'   => auth()->id() ?? 1,
            'nombre_documento' => $doc->nombre_original,
            'accion'       => 'INACTIVAR',
            'ruta_anterior'=> $doc->ruta_completa,
            'ruta_nueva'   => null
        ]);

        /* RESPUESTA API */
        if ($request -> expectsJson()){
            return response()->json([
                'success'=>true,
                'message'=>'Documento inactivado exitosamente',
            ]);
        }


        /** RESPUESTA WEB */
        return redirect()->back()
            ->with('success', 'Documento Inactivado exitosamente');
        
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
    public function edit(Request $request,Documento $documento)
    {
        return view('documentos.edit', [
            'documento' => $documento,
            'from' => $request->query('from'),
            'areas' => Area::where('estado', 1)->get(),
            'categorias' => Categoria::where('estado', 1)->get(),
            'empresas' => Empresa::where('estado',1)->get(),
            #'colaboradores' => Colaborador::where('estado',1)->get(),
            #'contratos' => Contrato::where('estado',1)->with(['colaborador','empresa','informacionAdicional'])->get(),
            'contratosEmpresa' => ContratoEmpresa::where('estado',1)->with(['empresa','informacionAdicionalEmpresa'])->get()
        ]);
    }

    public function update(Request $request, Documento $documento)
    {
        $request->validate([
            'area_id' => 'required',
            'categoria_id' => 'required',
            'descripcion' => 'nullable|string',
            'tipo_propietario' => 'nullable|in:empresa,colaborador,contrato,contratoEmpresa',
            'empresa_id' => 'required_if:tipo_propietario,empresa|nullable|exists:empresa,id_empresa',
            'colaborador_id' => 'required_if:tipo_propietario,colaborador|nullable|exists:colaborador,id_colaborador',
            'contrato_id' => 'required_if:tipo_propietario,contrato|nullable|exists:contratos,id_contrato',
            'contrato_empresa_id' => 'required_if:tipo_propietario,contratoEmpresa|nullable|exists:contrato_empresa,id_contrato_empresa'
        ]);

        DB::beginTransaction();

        try {
            $categoriaNueva = Categoria::findOrFail($request->categoria_id);
            $areaNueva = Area::findOrFail($request->area_id);
            $anio = $documento->anio;

            // === Determinar Propietario del archivo === //
            $ownerType = null;
            $ownerId = null;

            if ($request->filled('tipo_propietario')) 
            {
                if ($request->tipo_propietario === 'empresa' && $request->filled('empresa_id')) {
                    $ownerType = 'empresa';
                    $ownerId = $request->empresa_id;
                    $empresaNueva = Empresa::findOrFail($ownerId);
                    $identificador = $empresaNueva -> nombre_empresa;
                } elseif ($request->tipo_propietario === 'colaborador' && $request->filled('colaborador_id')) {
                    $ownerType = 'colaborador';
                    $ownerId = $request->colaborador_id;
                    $colaboradorNuevo = Colaborador::findOrFail($ownerId);
                    $identificador = $colaboradorNuevo -> numero_identificacion;
                }elseif ($request->tipo_propietario === 'contrato' && $request->filled('contrato_id')) {
                    $ownerType    = 'contrato';
                    $ownerId      = $request->contrato_id;
                    $contrato     = Contrato::with('colaborador')->findOrFail($ownerId);
                    $identificador = $contrato->colaborador->numero_identificacion . 
                    '-' . $contrato->informacionAdicional->cargo . '-' . $contrato->informacionAdicional->fecha_inicial
                    .'-'. $contrato->informacionAdicional->fecha_terminacion;
                }elseif ($request->tipo_propietario === 'contratoEmpresa' && $request->filled('contrato_empresa_id')) {
                    $ownerType       = 'contratoEmpresa';
                    $ownerId         = $request->contrato_empresa_id;
                    $contratoEmpresa = ContratoEmpresa::with('empresa')->findOrFail($ownerId);
                    $identificador   = $contratoEmpresa->empresa->nombre_empresa . '-' .
                    $contratoEmpresa->informacionAdicionalEmpresa->inicio_contrato . '-' .
                    $contratoEmpresa->informacionAdicionalEmpresa->finalizacion_contrato;
                }
            }

            // === RUTA ACTUAL ===
            $rutaActual = public_path($documento->ruta_completa);

            // === NUEVA RUTA ===
            $nuevaRutaFisica = public_path(
                'bodega_documental/' .
                Str::slug($ownerType) . '/' .
                Str::slug($identificador). '/' .
                Str::slug($categoriaNueva->nombre) . '/' .
                Str::slug($areaNueva->nombre) . '/' .
                $anio
            );

            // Crear carpetas si no existen
            if (!file_exists($nuevaRutaFisica)) {
                mkdir($nuevaRutaFisica, 0775, true);
            }

            $nuevaRutaBD = 'bodega_documental/' .
                Str::slug($ownerType) . '/' .
                Str::slug($identificador). '/' .
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
                'usuario_id' => auth()->id() ?? 1,
                'nombre_documento' => $documento->nombre_original,
                'accion' => 'EDITAR',
                'ruta_anterior' => $documento->getOriginal('ruta_completa'),
                'ruta_nueva' => $nuevaRutaBD
            ]);

            DB::commit();

            /* RESPUESTA API */
            if ($request->expectsJson()){
                return response() -> json([
                    'success'=> true,
                    'message'=> 'Informacion del documento actualizado',
                    'data'=> $documento->fresh()
                ]);
            }

            if($request -> filled('from')){
                return redirect($request->from)
                    ->with('success','Documento actualizado correctamente');
            }
            return redirect()
                ->route('documentos.index')
                ->with('success', 'Documento actualizado correctamente');

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function createParaContrato(Contrato $contrato)
    {
        $contrato->load(['colaborador', 'empresa']);
    
        return view('documentos.partials.form-contrato', [
            'contrato'   => $contrato,
            'areas'      => Area::where('estado', 1)->get(),
            'categorias' => Categoria::where('estado', 1)->get(),
        ]);
    }

    public function createParaContratoEmpresa(ContratoEmpresa $contratoEmpresa)
    {
        $contratoEmpresa->load(['empresa']);

        return view('documentos.partials.form-contrato-empresa', [
            'contratoEmpresa' => $contratoEmpresa,
            'areas'           => Area::where('estado', 1)->get(),
            'categorias'      => Categoria::where('estado', 1)->get(),
        ]);
    }
    

    // Buscar colaboradores
    public function searchColaboradores(Request $request)
    {
        $q = $request->get('q', '');

        $colaboradores = Colaborador::where('estado', 1)
            ->where(function($query) use ($q) {
                $query->where('primer_nombre', 'like', "%$q%")
                    ->orWhere('primer_apellido', 'like', "%$q%")
                    ->orWhere('numero_identificacion', 'like', "%$q%");
        })
        ->limit(50)
        ->get(['id_colaborador', 'primer_nombre', 'segundo_nombre', 'primer_apellido', 'segundo_apellido', 'numero_identificacion']);

        return response()->json([
            'results' => $colaboradores->map(fn($c) => [
                'id'                     => $c->id_colaborador,
                'text'                   => "{$c->primer_nombre} {$c->primer_apellido} - {$c->numero_identificacion}",
                'primer_nombre'          => $c->primer_nombre,
                'segundo_nombre'         => $c->segundo_nombre,
                'primer_apellido'        => $c->primer_apellido,
                'segundo_apellido'       => $c->segundo_apellido,
                'numero_identificacion'  => $c->numero_identificacion,
        ])
    ]);
}

    // Buscar contratos de colaborador
    public function searchContratos(Request $request)
    {
        $q = $request->get('q', '');

        $contratos = Contrato::where('estado', 1)
            ->with(['colaborador', 'empresa', 'informacionAdicional'])
            ->whereHas('colaborador', fn($query) =>
                $query->where('primer_nombre', 'like', "%$q%")
                    ->orWhere('primer_apellido', 'like', "%$q%")
                    ->orWhere('numero_identificacion', 'like', "%$q%")
            )
            ->limit(50)
            ->get();

        return response()->json([
            'results' => $contratos->map(fn($c) => [
                'id'                    => $c->id_contrato,
                'text'                  => "{$c->colaborador->primer_nombre} {$c->colaborador->primer_apellido}",
                'primer_nombre'         => $c->colaborador->primer_nombre,
                'primer_apellido'       => $c->colaborador->primer_apellido,
                'numero_identificacion' => $c->colaborador->numero_identificacion,
                'empresa'               => $c->empresa->nombre_empresa,
                'cargo'                 => $c->informacionAdicional->cargo,
                
            ])
        ]);
    }

    public function activar(Documento $documento)
    {
        $documento->update(['estado' => 1]);

        HistorialDocumento::create([
            'documento_id' => $documento->id,
            'usuario_id' => auth()->id() ?? 1,
            'nombre_documento' => $documento->nombre_original,
            'accion' => 'Activar',
            'ruta_anterior' => null,
            'ruta_nueva' => $documento->getOriginal('ruta_completa')
        ]);

        return redirect()->back()->with('success', 'Documento activado correctamente.');
    }

    public function eliminarPermanente(Documento $documento)
    {
        if (!$documento->puedeEliminarse()) {
            return redirect()->back()->with('error', 'Este documento aún no puede eliminarse permanentemente.');
        }

        // Elimina el archivo físico directamente de la carpeta public/
        if ($documento->ruta_completa) {
            $rutaFisica = public_path($documento->ruta_completa);

            if (file_exists($rutaFisica)) {
                unlink($rutaFisica);
            }
        }

        HistorialDocumento::create([
                'documento_id' => $documento->id,
                'usuario_id' => auth()->id() ?? 1,
                'nombre_documento' => $documento->nombre_original,
                'accion' => 'ELIMINAR PERMANENTE',
                'ruta_anterior' => $documento->getOriginal('ruta_completa'),
                'ruta_nueva' => '-'
            ]);

        $documento->delete();

        return redirect()->back()->with('success', 'Documento eliminado permanentemente.');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colaborador;
use App\Models\Empresa;
use App\Models\Identificacion;
use Yajra\DataTables\Facades\DataTables;

class ColaboradorController extends Controller
{
    public function index(Request $request)
    {
        
        /* RESPUESTA API */
        if ($request->expectsJson() && !$request->ajax()) {
            $colaboradores = Colaborador::with('identificacion')->paginate(50);
            return response()->json([
                'success' => true,
                'data'    => $colaboradores->items(),
                'meta'    => [
                    'total'        => $colaboradores->total(),
                    'current_page' => $colaboradores->currentPage(),
                    'last_page'    => $colaboradores->lastPage(),
                ]
            ]);
        }

        /* RESPUESTA DATATABLES (ajax interno de la vista) */
        if ($request->ajax()) {
            $query = Colaborador::with('identificacion')->select('colaborador.*')->orderBy('id_colaborador', 'asc');

            return DataTables::of($query)
                ->addColumn('tipo_identificacion', fn($c) => $c->identificacion->tipo_identificacion ?? '')
                ->addColumn('nombre_completo', fn($c) => trim("$c->primer_nombre $c->segundo_nombre $c->primer_apellido $c->segundo_apellido"))
                ->addColumn('estado_badge', fn($c) => $c->estado
                    ? '<span class="badge bg-success">Activo</span>'
                    : '<span class="badge bg-secondary">Inactivo</span>')
                ->rawColumns(['estado_badge'])
                ->make(true);
        }

        /* RESPUESTA WEB */
        return view('colaboradores.index');
    }

    public function create()
    {
        $identificaciones = Identificacion::where('estado',1)->get();

        return view('colaboradores.create', compact('identificaciones'));
        
    }

    public function store(Request $request){

        $request->validate([
            'id_tipo_identificacion' => 'required|exists:identificacion,id_identificacion',
    
            'numero_identificacion' => 'required|string|max:50|unique:colaborador,numero_identificacion',
    
            'primer_nombre' => 'required|string|max:100',
            'segundo_nombre' => 'nullable|string|max:100',
    
            'primer_apellido' => 'required|string|max:100',
            'segundo_apellido' => 'nullable|string|max:100',
    
            'fecha_nacimiento' => 'nullable|date',
    
            'genero' => 'nullable|string|max:20',
    
            'direccion' => 'nullable|string|max:255',
            'ciudad' => 'nullable|string|max:100',
            'barrio' => 'nullable|string|max:100',
    
            'estado_civil' => 'nullable|string|max:50',
    
            'telefono_residencial' => 'nullable|string|max:20',
            'telefono_celular' => 'nullable|string|max:20',
    
            'estudios' => 'nullable|string|max:255'
    
            
        ]);

        $colaborador = Colaborador::create([
            'id_tipo_identificacion' => $request->id_tipo_identificacion,
            'numero_identificacion' => $request->numero_identificacion,
            'primer_nombre' => $request->primer_nombre,
            'segundo_nombre' => $request->segundo_nombre,
            'primer_apellido' => $request->primer_apellido,
            'segundo_apellido' => $request->segundo_apellido,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'genero' => $request->genero,
            'direccion' => $request->direccion,
            'ciudad' => $request->ciudad,
            'barrio' => $request->barrio,
            'estado_civil' => $request->estado_civil,
            'telefono_residencial' => $request->telefono_residencial,
            'telefono_celular' => $request->telefono_celular,
            'estudios' => $request->estudios,
            'estado' => 0
        ]);

        /* RESPUESTA API */
        if($request->expectsJson()){
            return response()->json([
                'success'=>true,
                'message'=>'Colaborador Creado Exitosamente',
                'data'=> $colaborador
            ]);
        }

        /** RESPUESTA WEB */
        return redirect()
            ->route('colaboradores.index')
            ->with('success', 'Colaborador creado Exitosamente');
    }

    public function edit(Colaborador $colaborador)
    {
        
        $identificaciones =Identificacion::where('estado',1)->get();

        return view('colaboradores.edit',
        compact(
            'colaborador',
            'identificaciones'
        ));
    }

    public function update(Request $request, Colaborador $colaborador)
    {
        $request -> validate([
            'id_tipo_identificacion' => 'required|exists:identificacion,id_identificacion',

            'numero_identificacion' => 'required|string|max:50|unique:colaborador,numero_identificacion,' . $colaborador->id_colaborador . ',id_colaborador',

            'primer_nombre' => 'required|string|max:100',
            'segundo_nombre' => 'nullable|string|max:100',
            'primer_apellido' => 'required|string|max:100',
            'segundo_apellido' => 'nullable|string|max:100',

            'fecha_nacimiento' => 'nullable|date',
            'genero' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'ciudad' => 'nullable|string|max:100',
            'barrio' => 'nullable|string|max:100',
            'estado_civil' => 'nullable|string|max:50',
            'telefono_residencial' => 'nullable|string|max:20',
            'telefono_celular' => 'nullable|string|max:20',
            'estudios' => 'nullable|string|max:255'
        ]);

        $colaborador->update($request->only([
            'id_tipo_identificacion',
            'numero_identificacion',
            'primer_nombre',
            'segundo_nombre',
            'primer_apellido',
            'segundo_apellido',
            'fecha_nacimiento',
            'genero',
            'direccion',
            'ciudad',
            'barrio',
            'estado_civil',
            'telefono_residencial',
            'telefono_celular',
            'estudios'
        ]));

        /* RESPUESTA API */
        if($request->expectsJson()){
            return response()->json([
                'success'=>true,
                'message'=>'Colaborador editado exitosamente',
                'data'=> $colaborador
            ]);
        }

        /** RESPUESTA WEB */
        return redirect()
            ->route('colaboradores.index')
            ->with('success', 'Colaborador actualizado correctamente');
    }

    public function inactivar(Colaborador $colaborador , Request $request)
    {

        if ($colaborador->estado == 0) {
            /* RESPUESTA API error */
            if($request->expectsJson()){
                return response()->json([
                    'success'=>false,
                    'message'=>'El colaborador ya esta inactivo'
                ]);
            }

            return redirect()
            ->route('colaboradores.index')
            ->with('success', 'El colaborador ya está inactivo.');
        }

        $colaborador->update([
            'estado' => 0
        ]);

        /* RESPUESTA API */
        if($request->expectsJson()){
            return response()->json([
                'success'=>true,
                'data'=> [
                    "colaborador"=>$colaborador->numero_identificacion,
                    "Nuevo Estado"=>$colaborador->estado
                ]
            ]);
        }

        /** RESPUESTA WEB */
        return redirect()
            ->route('colaboradores.index')
            ->with('success', 'Colaborador inactivado correctamente.');
        }

    public function activar(Colaborador $colaborador)
    {
        if ($colaborador->estado == 1) {
        return redirect()
            ->route('colaboradores.index')
            ->with('success', 'El colaborador ya está activo.');
        }

        $colaborador->update([
            'estado' => 1
        ]);
    
        return redirect()
            ->route('colaboradores.index')
            ->with('success', 'Colaborador activado correctamente.');
    
        }

    public function documentos(Colaborador $colaborador)
    {
        $documentos = $colaborador->documentos()
            ->with(['categoria','area','usuario'])
            ->orderBy('created_at')
            ->get();

        return view('colaboradores.documentos', compact('colaborador','documentos'));
    }

    public function detalle(Colaborador $colaborador)
    {
        $colaborador->load('identificacion');

        $contratoActivo = $colaborador->contratos()
            ->where('estado', 1)
            ->with(['empresa', 'informacionAdicional'])
            ->first();
        
        $contratosInactivos = $colaborador->contratos()
            ->where('estado',0)
            ->with(['empresa','informacionAdicional'])
            ->orderBy('id_contrato','desc')
            ->get();

        return view('colaboradores.detalle', compact('colaborador', 'contratoActivo','contratosInactivos'));
    }
}

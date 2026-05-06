<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;


class EmpresaController extends Controller
{
    public function index(Request $request)
    {
        $empresas = Empresa::orderBy('estado', 'desc')->get();

        /** RESPUESTA API **/

        if ($request->expectsJson()){
            return response()->json([
                'success'=> true,
                'data'=> $empresas
            ]);
        }

        /** RESPUESTA WEB */
        return view('empresas.index',compact('empresas'));
    }

    public function create()
    {
        return view('empresas.create');
    }

    public function store(Request $request)
    {
        $request ->validate([
            'nit' => 'required|numeric|unique:empresa,nit',
            
            'nombre_empresa' => 'required|string|max:255',

            'actividad' => 'nullable|string|max:255',

            'ciudad' => 'required|string|max:150',

            'direccion' => 'nullable|string|max:255',

            'barrio' => 'nullable|string|max:150',

            'telefono' => 'nullable|numeric'
        ]);

        $empresa = Empresa::create([
            'nit' => $request -> nit,
            'nombre_empresa' => $request -> nombre_empresa,
            'actividad' => $request -> actividad,
            'ciudad' => $request -> ciudad,
            'direccion' => $request -> direccion,
            'barrio' => $request -> barrio,
            'telefono' => $request -> telefono,
            'estado' => 0
        ]);

        /** RESPUESTA API **/

        if ($request -> expectsJson()){
            return response() ->json([
                'success' => true,
                'message' => 'Se creo la Empresa exitosamente',
                'data' => $empresa 
            ]);
        }


        /** RESPUESTA WEB **/
        return redirect()
            ->route('empresas.index')
            ->with('success', 'Empresa creada exitosamente.');
    }

    public function edit(Empresa $empresa)
    {
        return view('empresas.edit', compact('empresa'));
    }

    public function update(Request $request, Empresa $empresa)
    {
        $request ->validate([
            'nit' => 'required|numeric|unique:empresa,nit,'. $empresa->id_empresa . ',id_empresa',
            
            'nombre_empresa' => 'required|string|max:255',

            'actividad' => 'nullable|string|max:255',

            'ciudad' => 'required|string|max:150',

            'direccion' => 'nullable|string|max:255',

            'barrio' => 'nullable|string|max:150',

            'telefono' => 'nullable|numeric'
        ]);

        $empresa->update($request->only([
            'nit',
            'nombre_empresa',
            'actividad',
            'ciudad',
            'direccion',
            'barrio',
            'telefono'
        ]));

        /* RESPUESTA API */
        if ($request->expectsJson()){
            return response()->json([
                'success'=> true,
                'message'=> 'Edito la empresa de manera exitosa',
                'data' => $empresa
            ]);
        }

        /* RESPUESTA WEB */
        return redirect()
            ->route('empresas.index')
            ->with('success', 'Empresa actualizada exitosamente.');
    }

    public function inactivar(Empresa $empresa, Request $request)
    {    
        if ($empresa->estado == 0)
        {   
            /* RESPUESTA API error */
            if($request->expectsJson()){
                return response()->json([
                    'success'=>false,
                    'message'=>'La empresa ya esta inactiva'
                ]);
            }
            return redirect()
                ->route('empresas.index')
                ->with('success','la empresa ya esta inactiva');
        }

        $empresa->update([
            'estado' => 0
        ]);

        /* RESPUESTA API */
        if ($request -> expectsJson()){
            return response()->json([
                'success'=>'Se Inactivo la empresa exitosamente',
                'data'=>[
                    'empresa'=> $empresa->nombre_empresa,
                    'estado' => $empresa->estado
                ]
            ]);
        }

        /* RESPUESTA WEB */
        return redirect()
            ->route('empresas.index')
            ->with('success','Empresa inactivada Exitosamente');
    }
    
    public function activar(Empresa $empresa){
        if ($empresa->estado == 1){
            return redirect()
                ->route('empresas.index')
                ->with('success','la empresa ya esta activa');
        }

        $empresa->update([
            'estado' =>1
        
        ]);

        return redirect()
            ->route('empresas.index')
            ->with('success','Empresa Activada Exitosamente');
    }

    public function documentos(Empresa $empresa)
    {
        $documentos = $empresa->documentos()
            ->with(['categoria','area','usuario'])
            ->orderBy('created_at')
            ->get();
        
        return view('empresas.documentos', compact('empresa','documentos'));
    }

    public function detalle(Empresa $empresa)
    {
        $contratoActivo = $empresa->contratosEmpresa()
            ->where('estado', 1)
            ->with(['informacionAdicionalEmpresa'])
            ->first();

        $contratosInactivos = $empresa->contratosEmpresa()
            ->where('estado', 0)
            ->with(['informacionAdicionalEmpresa'])
            ->orderBy('updated_at', 'desc')
            ->get();
    
        return view('empresas.detalle', compact('empresa', 'contratoActivo', 'contratosInactivos'));
    }
}

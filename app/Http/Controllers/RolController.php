<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\PermisoRol;
use Illuminate\Http\Request;

class RolController extends Controller
{
    public function index()
    {
        $roles = Rol::where('estado', 1)
            ->withCount('usuarios')
            ->orderBy('nombre')
            ->get();

        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        // Permisos disponibles en el sistema
        $permisos = [
            'usuarios.ver',
            'usuarios.crear',
            'usuarios.editar',
            'usuarios.eliminar',

            'roles.ver',
            'roles.crear',
            'roles.editar',
            'roles.eliminar',

            'documentos.ver',
            'documentos.crear',
            'documentos.editar',
            'documentos.eliminar',
            'documentos.mover',

            'configuracion.editar',

            'areas.ver',
            'areas.editar',
            'areas.crear',
            'areas.eliminar',

            'historial.ver',

            'categorias.ver',
            'categorias.crear',
            'categorias.editar',
            'categorias.eliminar',

            'colaboradores.ver',
            'colaboradores.crear',
            'colaboradores.editar',
            'colaboradores.eliminar',

            'empresas.ver',
            'empresas.crear',
            'empresas.editar',
            'empresas.eliminar'

        ];

        return view('roles.create', compact('permisos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:roles,nombre',
            'descripcion' => 'nullable|string',
            'permisos' => 'array'
        ]);

        $rol = Rol::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion
        ]);

        // Asignar permisos SOLO si es Administrador o si se seleccionan
        if ($request->has('permisos')) {
            foreach ($request->permisos as $permiso) {
                PermisoRol::create([
                    'rol_id' => $rol->id,
                    'permiso' => $permiso
                ]);
            }
        }

        return redirect()
            ->route('roles.index')
            ->with('success', 'Rol creado correctamente');
    }

    public function edit(Rol $rol)
    {
        // Lista TOTAL de permisos disponibles
        $permisos = [
                        'usuarios.ver',
            'usuarios.crear',
            'usuarios.editar',
            'usuarios.eliminar',

            'roles.ver',
            'roles.crear',
            'roles.editar',
            'roles.eliminar',

            'documentos.ver',
            'documentos.crear',
            'documentos.editar',
            'documentos.eliminar',
            'documentos.mover',

            'configuracion.editar',

            'areas.ver',
            'areas.editar',
            'areas.crear',
            'areas.eliminar',

            'historial.ver',

            'categorias.ver',
            'categorias.crear',
            'categorias.editar',
            'categorias.eliminar',

            'colaboradores.ver',
            'colaboradores.crear',
            'colaboradores.editar',
            'colaboradores.eliminar',

            'empresas.ver',
            'empresas.crear',
            'empresas.editar',
            'empresas.eliminar'
        ];

        // Permisos YA asignados al rol
        $asignados = $rol->permisos()
            ->pluck('permiso')
            ->toArray();

        return view('roles.edit', compact('rol', 'permisos', 'asignados'));
    }


    public function update(Request $request, Rol $rol)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:roles,nombre,' . $rol->id,
            'descripcion' => 'nullable|string',
            'permisos' => 'array'
        ]);

        $rol->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion
        ]);

        // Reemplazar permisos
        PermisoRol::where('rol_id', $rol->id)->delete();

        if ($request->has('permisos')) {
            foreach ($request->permisos as $permiso) {
                PermisoRol::create([
                    'rol_id' => $rol->id,
                    'permiso' => $permiso
                ]);
            }
        }

        return redirect()
            ->route('roles.index')
            ->with('success', 'Rol actualizado correctamente');
    }

    public function destroy(Rol $rol)
    {
        // Evitar eliminar Administrador
        if ($rol->nombre === 'Administrador') {
            return redirect()
                ->route('roles.index')
                ->with('error', 'No se puede eliminar el rol Administrador');
        }

        // Verificar si tiene usuarios asignados
        if ($rol->usuarios()->count() > 0) {
            return redirect()
                ->route('roles.index')
                ->with('error', 'No se puede eliminar el rol porque tiene usuarios asignados');
        }

        // Verificar si tiene permisos asignados
        if ($rol->permisos()->count() > 0) {
            return redirect()
                ->route('roles.index')
                ->with('error', 'No se puede eliminar el rol porque tiene permisos asignados');
        }

        $rol->delete();

        return redirect()
            ->route('roles.index')
            ->with('success', 'Rol eliminado correctamente');
    }

    public function inactivar(Rol $rol)
    {
        if ($rol->nombre === 'Administrador') {
            return redirect()
                ->route('roles.index')
                ->with('error', 'No se puede inactivar el rol Administrador');
        }

        if ($rol->usuarios()->count() > 0) {
            return redirect()
                ->route('roles.index')
                ->with('error', 'No se puede inactivar un rol con usuarios asignados');
        }

        $rol->update(['estado' => 0]);

        return redirect()
            ->route('roles.index')
            ->with('success', 'Rol inactivado correctamente');
    }


    public function activar(Rol $rol)
    {
        $rol->estado = true;
        $rol->save();

        return redirect()
            ->route('roles.index')
            ->with('success', 'Rol activado correctamente');
    }


}

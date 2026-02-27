<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class Usuario extends Authenticatable
{
    protected $table = 'usuarios';
    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'nombre', 'email', 'password', 'rol_id', 'estado'
    ];

    protected $hidden = ['password'];

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function rol()
    {
        return DB::table('roles')->where('id', $this->rol_id)->first();
    }

    public function tienePermiso($permiso)
    {
        return DB::table('permisos_rol')
            ->where('rol_id', $this->rol_id)
            ->where('permiso', $permiso)
            ->exists();
    }

    public function esAdministrador()
    {
        $rol = $this->rol();
        return $rol && $rol->nombre === 'Administrador';
    }

    public function esGestor()
    {
        $rol = $this->rol();
        return $rol && $rol->nombre === 'Gestor Documental';
    }

    public function esViewer()
    {
        $rol = $this->rol();
        return $rol && $rol->nombre === 'Viewer Documental';
    }
}
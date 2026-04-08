<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens,Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'email',
        'password',
        'rol_id',
        'estado'
    ];

    protected $hidden = ['password'];

    /* ======================
     | Relaciones
     ====================== */

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    /* ======================
     | Permisos
     ====================== */

    public function permisos()
    {
        return $this->rol
            ? $this->rol->permisos()
            : collect();
    }

    public function tienePermiso(string $permiso): bool
    {
        return $this->rol
            && $this->rol->permisos()
                ->where('permiso', $permiso)
                ->exists();
    }

    /* ======================
     | Helpers de rol
     ====================== */

    public function esAdministrador(): bool
    {
        return $this->rol && $this->rol->nombre === 'Administrador';
    }

    public function esGestor(): bool
    {
        return $this->rol && $this->rol->nombre === 'Gestor Documental';
    }

    public function esViewer(): bool
    {
        return $this->rol && $this->rol->nombre === 'Viewer Documental';
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresa';
    protected $primaryKey = 'id_empresa';

    protected $fillable = [
        'nit',
        'nombre_empresa',
        'actividad',
        'ciudad',
        'direccion',
        'barrio',
        'telefono',
        'estado'
    ];

    public $timestamps = true;

    public function colaboradores()
    {
        return $this->hasMany(Colaborador::class, 'id_empresa');
    }

    public function documentos()
    {
        return $this->morphMany(Documento::class, 'owner');
    }
}

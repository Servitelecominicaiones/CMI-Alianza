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

    public function documentos()
    {
        return $this->morphMany(Documento::class, 'owner');
    }

    public function contratos(){
        return $this-> hasMany(Contrato::class, 'id_empresa');
    }

    public function contratosEmpresa(){
        return $this->hasMany(ContratoEmpresa::class, 'id_empresa');
    }

    public function contratoEmpresaActivo()
    {
        return $this->contratosEmpresa()
            ->where('estado', 1)
            ->exists();
    }
}

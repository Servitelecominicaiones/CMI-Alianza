<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Colaborador extends Model
{
    protected $table = 'colaborador';
    protected $primaryKey = 'id_colaborador';

    protected $fillable = [
        'id_empresa',
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
        'estudios',
        'estado'
    ];

    public $timestamps = true;

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'id_empresa');
    }

    public function identificacion()
    {
        return $this->belongsTo(Identificacion::class, 'id_tipo_identificacion');
    }

    public function informacionAdicional()
    {
        return $this->hasOne(InformacionAdicionalColaborador::class, 'id_colaborador');
    }

    public function documentos()
    {
        return $this->morphMany(Documento::class, 'owner');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Contrato extends Model
{
    protected $table = 'contratos';

    protected $primaryKey = 'id_contrato';

    protected $fillable = [
        'id_empresa',
        'id_colaborador',
        'id_informacion_adicional',
        'inicio_contrato',
        'finalizacion_contrato',
        'estado',
        'motivo_inactivacion'
    ];

    public function empresa(){
        return $this->belongsTo(Empresa::class, 'id_empresa');
    }

    public function colaborador(){
        return $this->belongsTo(Colaborador::class, 'id_colaborador');
    }

    public function informacionAdicional(){
        return $this->belongsTo(InformacionAdicionalColaborador::class, 'id_informacion_adicional');
    }

}

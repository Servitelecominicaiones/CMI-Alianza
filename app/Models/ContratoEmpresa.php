<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContratoEmpresa extends Model
{
    protected $table = 'contrato_empresa';

    protected $primaryKey = 'id_contrato_empresa';

    protected $fillable = [
        'id_empresa',
        'id_informacion_adicional_empresa',
        'estado',
        'motivo_inactivacion'
    ];

    public function empresa(){
        return $this->belongsTo(Empresa::class, 'id_empresa');
    }

    public function informacion_adicional_empresa(){
        return $this->belongsTo(InformacionAdicionalEmpresa::class, 'id_informacion_adicional_empresa');
    }
}

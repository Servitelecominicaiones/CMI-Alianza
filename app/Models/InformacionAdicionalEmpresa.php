<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InformacionAdicionalEmpresa extends Model
{
    protected $table = 'informacion_adicional_empresa';

    protected $primaryKey = 'id_informacion_adicional_empresa';

    protected $fillable =[
        'inicio_contrato',
        'finalizacion_contrato'
    ];

    public function contratosEmpresa(){
        return $this->hasMany(ContratoEmpresa::class,'id_informacion_adicional_empresa');
    }
}

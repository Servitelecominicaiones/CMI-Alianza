<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InformacionAdicionalColaborador extends Model
{
    protected $table = 'informacion_adicional_colaborador';
    protected $primaryKey = 'id_informacion_adicional';

    protected $fillable = [
        'id_colaborador',
        'cargo',
        'eps',
        'fondo',
        'caja_compensacion',
        'salario_basico',
        'sub_transporte',
        'bono_servicio',
        'factor_prestacional',
        'bono_salud_y_vivienda',
        'medios_transporte',
        'prima_riesgo',
        'auxilio_formacion',
        'comision_fija',
        'productividad_fija',
        'tiempo_extra_fijo',
        'bono_mercado',
        'auxilio_equipo',
        'recargo_nocturno',
        'trans_adicional',
        'fecha_inicial',
        'fecha_terminacion',
        'cajaUbica',
        'carpetaIn',
        'carpetaFin'
    ];

    public $timestamps = false;

    public function contratos(){
        return $this->hasMany(Contrato::class, 'id_informacion_adicional');
    }
}

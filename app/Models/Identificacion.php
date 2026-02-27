<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Identificacion extends Model
{
    protected $table = 'identificacion';
    protected $primaryKey = 'id_identificacion';

    protected $fillable = [
        'tipo_identificacion',
        'estado'
    ];

    public $timestamps = true;

    public function colaboradores()
    {
        return $this->hasMany(Colaborador::class, 'id_tipo_identificacion');
    }
}

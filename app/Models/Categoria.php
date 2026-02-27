<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';

    public $timestamps = false; // solo tiene created_at

    protected $fillable = [
        'nombre',
        'estado'
    ];
}

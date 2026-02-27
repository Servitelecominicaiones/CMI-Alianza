<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class HistorialDocumento extends Model
{
    protected $table = 'historial_documentos';

    public $timestamps = false; // correcto

    protected $fillable = [
        'documento_id',
        'usuario_id',
        'accion',
        'ruta_anterior',
        'ruta_nueva'
    ];

    // 👇 CAST MANUAL
    protected $casts = [
        'created_at' => 'datetime',
    ];

    /* ================= RELACIONES ================= */

    public function documento()
    {
        return $this->belongsTo(Documento::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}

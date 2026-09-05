<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Documento extends Model
{
    protected $table = 'documentos';

    protected $fillable = [
        'nombre_original',
        'nombre_archivo',
        'ruta_completa',
        'area_id',
        'categoria_id',
        'anio',
        'extension',
        'tamanio',
        'descripcion',
        'usuario_carga_id',
        'estado',
        'owner_id',      
        'owner_type'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /* ================= RELACIONES ================= */

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_carga_id');
    }

    /* ================= RELACIÓN POLIMÓRFICA ================= */

    public function owner()
    {
        return $this->morphTo();
    }

    /* ================= LÓGICA ================= */

    public function puedeEliminarse()
    {
        if ($this->estado == 1) return false;

        return $this->updated_at->diffInDays(now()) >= 60;
    }

    public function diasRestantesParaEliminar()
    {
        if ($this->estado == 1) {
            return null; // No aplica: el documento sigue activo
        }

        $diasTranscurridos = $this->updated_at->diffInDays(now());
        $diasFaltantes = 60 - $diasTranscurridos;

        return (int) max($diasFaltantes, 0);
    }

    public function previewUrl()
    {
        // Ruta protegida
        return route('documentos.preview', $this->id);
    }
    public function historial()
    {
        return $this->hasMany(HistorialDocumento::class);
    }

    /* ================= PROPIETARIO (OWNER) ================= */

    public function getOwnerTipoLabelAttribute()
    {
        return match ($this->owner_type) {
            'empresa' => 'Empresa',
            'colaborador' => 'Colaborador',
            'contrato' => 'Contrato',
            'contratoEmpresa' => 'Contrato Empresa',
            default => '-',
        };
    }

    public function getOwnerLabelAttribute()
    {
        if (!$this->owner_type || !$this->owner) {
            return '-';
        }

        return match ($this->owner_type) {
            'empresa' => $this->owner->nombre_empresa,
            'colaborador' => trim($this->owner->primer_nombre . ' ' . $this->owner->primer_apellido)
                . ' - ' . $this->owner->numero_identificacion,
            'contrato' => trim(optional($this->owner->colaborador)->primer_nombre . ' ' . optional($this->owner->colaborador)->primer_apellido)
                . ' - ' . optional($this->owner->colaborador)->numero_identificacion,
            'contratoEmpresa' => optional($this->owner->empresa)->nombre_empresa,
            default => '-',
        };
    }
}

<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class HistorialDocumentosExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {
        return ['Fecha', 'Documento', 'Acción', 'Usuario', 'Ruta Anterior', 'Ruta Nueva'];
    }

    public function map($h): array
    {
        return [
            $h->created_at ? $h->created_at->format('Y-m-d H:i') : '—',
            $h->nombre_documento ?? 'Documento eliminado',
            $h->accion,
            $h->usuario->nombre ?? 'Sistema',
            $h->ruta_anterior ?? '-',
            $h->ruta_nueva ?? '-',
        ];
    }
}
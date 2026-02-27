@extends('layouts.app')

@section('title', 'Documentos del Colaborador')

@section('content')

<div class="container-fluid">
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2>Documentos de {{ $colaborador->primer_nombre }} {{ $colaborador->primer_apellido }}</h2>
                    <p class="text-muted mb-0">
                        <strong>ID:</strong> {{ $colaborador->numero_identificacion }} | 
                        <strong>Empresa:</strong> {{ $colaborador->empresa->nombre_empresa ?? 'N/A' }}
                    </p>
                </div>
                <a href="{{ route('colaboradores.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Volver a Colaboradores
                </a>
            </div>
        </div>
    </div>

    {{-- Contenido --}}
    @if($documentos->isEmpty())
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i>
            Este colaborador no tiene documentos asociados.
        </div>
    @else
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Total de documentos: {{ $documentos->count() }}</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nombre del Archivo</th>
                                <th>Categoría</th>
                                <th>Área</th>
                                <th>Año</th>
                                <th>Tamaño</th>
                                <th>Cargado por</th>
                                <th>Fecha de Carga</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documentos as $documento)
                                <tr>
                                    <td>
                                        <i class="bi bi-file-earmark-{{ $documento->extension }}"></i>
                                        {{ $documento->nombre_original }}
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ $documento->categoria->nombre ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>{{ $documento->area->nombre ?? 'N/A' }}</td>
                                    <td>{{ $documento->anio }}</td>
                                    <td>{{ number_format($documento->tamanio / 1024, 2) }} KB</td>
                                    <td>{{ $documento->usuario->nombre ?? 'Desconocido' }}</td>
                                    <td>{{ $documento->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ $documento->previewUrl() }}" 
                                           class="btn btn-sm btn-primary" 
                                           target="_blank"
                                           title="Ver documento">
                                            <i class="bi bi-eye"></i> Ver
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>

@endsection
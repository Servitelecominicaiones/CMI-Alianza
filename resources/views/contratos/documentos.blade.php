@extends('layouts.app')

@section('title', 'Documentos del Colaborador')

@section('content')

<div class="container">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h3 class="mb-0">
                Documentos del contrato:
                {{ $contrato->colaborador->primer_nombre }}
                {{ $contrato->colaborador->primer_apellido }}
            </h3>

            <p class="text-muted mb-0">
                <strong>Cargo:</strong> {{ $contrato->informacionAdicional->cargo }}
                <strong>Fecha Inicio:</strong> {{ $contrato->inicio_contrato }}
                <strong>Fecha Fin:</strong> {{ $contrato->finalizacion_contrato }}
            </p>

            <small class="text-muted">
                <strong>ID:</strong> {{ $contrato->colaborador->numero_identificacion }}
                —
                <strong>Empresa:</strong> {{ $contrato->empresa->nombre_empresa ?? 'N/A' }}
            </small>
        </div>

        <a href="{{ route('colaboradores.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>


    {{-- Tabla documentos --}}
    <div class="card">

        <div class="card-header bg-dark text-white">
            <i class="bi bi-file-earmark-text me-2"></i>
            Total de documentos: {{ $documentos->count() }}
        </div>

        <div class="card-body">

            @if($documentos->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-striped table-hover align-middle">

                        <thead class="table-dark">
                            <tr>
                                <th>Nombre del archivo</th>
                                <th>Categoría</th>
                                <th>Área</th>
                                <th>Año</th>
                                <th>Tamaño</th>
                                <th>Cargado por</th>
                                <th>Fecha de carga</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($documentos as $documento)
                                <tr>

                                    <td>
                                        <i class="bi bi-file-earmark-{{ $documento->extension }}"></i>
                                        {{ $documento->nombre_original }}
                                    </td>

                                    <td>{{ $documento->categoria->nombre ?? '—' }}</td>

                                    <td>{{ $documento->area->nombre ?? '—' }}</td>

                                    <td>{{ $documento->anio }}</td>

                                    <td>{{ number_format($documento->tamanio /1024,2)}} KB</td>

                                    <td>{{ $documento->usuario->nombre ?? 'Desconocido' }}</td>

                                    <td>{{ $documento->created_at->format('d/m/Y H:i') }}</td>

                                    <td class="text-center">
                                        <div class="d-flex justify-content-between">
                                            {{-- ver documento --}}
                                            <a href="{{ $documento->previewUrl() }}"
                                               target="_blank"
                                               class="btn btn-sm btn-primary"
                                               title="Ver documento"
                                               data-bs-toggle="tooltip">

                                                <i class="bi bi-eye"></i>

                                            </a>

                                            {{-- Editar Documento --}}
                                                <a href="{{ route('documentos.edit', $documento) }}"
                                                data-bs-toggle="tooltip"
                                                title="Editar Documento"
                                                class="btn btn-sm btn-warning">
                                                    <i class="bi bi-pencil"></i>
                                                </a>

                                            {{-- Inactivar --}}
                                            <form method="POST"
                                                action="{{ route('documentos.destroy',$documento)}}"
                                                class="d-inline"
                                                onsubmit="return confirmarInactivacion()">
                                                @csrf
                                                @method("PUT")
                                                    <button type="submit" class="btn btn-sm btn-danger" 
                                                    data-bs-toggle="tooltip" title="Inactivar Documento">
                                                    <i class="bi bi-trash"></i>
                                            </form>
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>

                    </table>

                </div>

            @else

                <p class="text-muted mb-0">
                    No hay documentos registrados para este contrato.
                </p>

            @endif

        </div>

    </div>

</div>

@endsection

@push('scripts')
    <script>
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
            new bootstrap.Tooltip(el);
        });
    </script>
@endpush
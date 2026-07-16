@extends('layouts.app')

@section('title', 'Documentos del Colaborador')

@section('content')

<div class="container">

    {{-- Header (sin cambios) --}}
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


    {{-- Tabla documentos activos --}}
    <div class="card mb-3">

        <div class="card-header bg-dark text-white">
            <i class="bi bi-file-earmark-text me-2"></i>
            Total de documentos activos: {{ $documentosActivos->count() }}
        </div>

        <div class="card-body">

            @if($documentosActivos->isNotEmpty())

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
                            @foreach($documentosActivos as $documento)
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

                                            @if (in_array('documentos.editar', session('permisos_usuario', [])) && $contrato->estado == 1)
                                                <a href="{{ route('documentos.edit', [$documento, 'from' => url()->current() ]) }}"
                                                data-bs-toggle="tooltip"
                                                title="Editar Documento"
                                                class="btn btn-sm btn-warning">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @endif

                                            @if (in_array('documentos.eliminar', session('permisos_usuario', []))&& $contrato->estado == 1)
                                                {{-- Inactivar --}}
                                                <form method="POST"
                                                    action="{{ route('documentos.destroy',$documento)}}"
                                                    class="d-inline form-inactivar">
                                                    @csrf
                                                    @method("PUT")
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        data-bs-toggle="tooltip" title="Inactivar Documento">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endif

                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>

                    </table>

                </div>

            @else

                <p class="text-muted mb-0">
                    No hay documentos activos registrados para este contrato.
                </p>

            @endif

        </div>

    </div>

    {{-- Bloque desplegable: Documentos inactivos --}}
    <div class="card">
        <div class="card-header bg-secondary text-white d-flex justify-content-start gap-3 align-items-center">
            <button class="btn btn-sm btn-light d-flex align-items-center gap-2"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#documentosInactivosCollapse"
                    aria-expanded="false"
                    aria-controls="documentosInactivosCollapse">
                <i class="bi bi-chevron-down"></i>
                Ver documentos inactivos ({{ $documentosInactivos->count() }})
            </button>

            <p class="mb-0">
                 (Solo se puede eliminar permanentemente un documento inactivo después de 60 días de inactivación)
            </p>
        </div>

        <div class="collapse" id="documentosInactivosCollapse">
            <div class="card-body">

                @if($documentosInactivos->isNotEmpty())

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
                                @foreach($documentosInactivos as $documento)
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
                                            <div class="d-flex justify-content-center gap-2">

                                                {{-- ver documento --}}
                                                <a href="{{ $documento->previewUrl() }}"
                                                   target="_blank"
                                                   class="btn btn-sm btn-primary"
                                                   title="Ver documento"
                                                   data-bs-toggle="tooltip">
                                                    <i class="bi bi-eye"></i>
                                                </a>

                                                @if (in_array('documentos.editar', session('permisos_usuario', [])))
                                                    {{-- Activar --}}
                                                    <form method="POST"
                                                        action="{{ route('documentos.activar', $documento) }}"
                                                        class="d-inline form-activar">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-success"
                                                            data-bs-toggle="tooltip" title="Activar Documento">
                                                            <i class="bi bi-arrow-counterclockwise"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                @if (in_array('documentos.eliminar', session('permisos_usuario', [])))
                                                    @php
                                                        $puedeEliminar = $documento->puedeEliminarse();
                                                        $diasFaltantes = $documento->diasRestantesParaEliminar();

                                                        $tituloEliminar = $puedeEliminar
                                                            ? 'Eliminar permanentemente'
                                                            : ($diasFaltantes === 1
                                                                ? 'Podrás eliminarlo en 1 día'
                                                                : "Podrás eliminarlo en {$diasFaltantes} días");
                                                    @endphp

                                                    {{-- Eliminar permanentemente --}}
                                                    <form method="POST"
                                                        action="{{ route('documentos.eliminarPermanente', $documento) }}"
                                                        class="d-inline form-eliminar-permanente">
                                                        @csrf
                                                        @method('DELETE')

                                                        <span tabindex="0"
                                                              data-bs-toggle="tooltip"
                                                              data-bs-trigger = "hover"
                                                              title="{{ $tituloEliminar }}"
                                                              {{ !$puedeEliminar ? 'style=display:inline-block' : '' }}>
                                                            <button type="submit" class="btn btn-sm btn-dark"
                                                                {{ $puedeEliminar ? '' : 'disabled' }}>
                                                                <i class="bi bi-trash3-fill"></i>
                                                            </button>
                                                        </span>
                                                    </form>
                                                @endif

                                            </div>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>

                        </table>

                    </div>

                @else

                    <p class="text-muted mb-0">
                        No hay documentos inactivos registrados para este contrato.
                    </p>

                @endif

            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
    <script>
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
            new bootstrap.Tooltip(el);
        });

        // Inactivar (confirmación)
        document.querySelectorAll('.form-inactivar').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Estás seguro(a) de inactivar este documento?',
                    text: 'Se inactivará el documento y no aparecerá en el dashboard',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, inactivar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#0d6efd',
                    cancelButtonColor: '#6c757d'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('loader-overlay').style.display = 'flex';
                        form.submit();
                    }
                });
            });
        });

        // Activar (confirmación)
        document.querySelectorAll('.form-activar').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Activar este documento nuevamente?',
                    text: 'El documento volverá a aparecer como activo',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, activar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#6c757d'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('loader-overlay').style.display = 'flex';
                        form.submit();
                    }
                });
            });
        });

        // Eliminar permanentemente (confirmación reforzada)
        document.querySelectorAll('.form-eliminar-permanente').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Eliminar permanentemente?',
                    text: 'Esta acción no se puede deshacer. El archivo y su registro se borrarán definitivamente.',
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar definitivamente',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('loader-overlay').style.display = 'flex';
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
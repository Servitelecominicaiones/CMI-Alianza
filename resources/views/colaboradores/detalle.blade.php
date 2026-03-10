@extends('layouts.app')

@section('title', 'Detalle del Colaborador')

@section('content')
<div class="container">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0">Detalle del Colaborador</h3>
            <small class="text-muted">
                {{ $colaborador->primer_nombre }}
                {{ $colaborador->segundo_nombre }}
                {{ $colaborador->primer_apellido }}
                {{ $colaborador->segundo_apellido }}
                &mdash; {{ $colaborador->numero_identificacion }}
                &mdash; {{ $contratoActivo->empresa->nombre_empresa ?? 'Sin empresa' }}
            </small>
        </div>
        <a href="{{ route('colaboradores.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    {{-- ══════════════════════════════════════════
         BLOQUE 1: Información Básica
    ═══════════════════════════════════════════ --}}

    {{-- Empresa e Identificación --}}
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <i class="bi bi-person-vcard me-2"></i> Identificación
        </div>
        <div class="card-body">
            <div class="row">

                <div class="col-md-4 mb-3">
                    <label class="form-label text-muted small">Empresa</label>
                    <p class="form-control-plaintext fw-semibold">
                        {{ $contratoActivo->empresa->nombre_empresa ?? '—' }}
                    </p>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label text-muted small">Tipo de Identificación</label>
                    <p class="form-control-plaintext fw-semibold">
                        {{ $colaborador->identificacion->tipo_identificacion ?? '—' }}
                    </p>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label text-muted small">Número de Identificación</label>
                    <p class="form-control-plaintext fw-semibold">
                        {{ $colaborador->numero_identificacion ?? '—' }}
                    </p>
                </div>

            </div>
        </div>
    </div>

    {{-- Datos Personales --}}
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <i class="bi bi-person me-2"></i> Datos Personales
        </div>
        <div class="card-body">
            <div class="row">

                <div class="col-md-3 mb-3">
                    <label class="form-label text-muted small">Primer Nombre</label>
                    <p class="form-control-plaintext fw-semibold">{{ $colaborador->primer_nombre ?? '—' }}</p>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label text-muted small">Segundo Nombre</label>
                    <p class="form-control-plaintext fw-semibold">{{ $colaborador->segundo_nombre ?? '—' }}</p>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label text-muted small">Primer Apellido</label>
                    <p class="form-control-plaintext fw-semibold">{{ $colaborador->primer_apellido ?? '—' }}</p>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label text-muted small">Segundo Apellido</label>
                    <p class="form-control-plaintext fw-semibold">{{ $colaborador->segundo_apellido ?? '—' }}</p>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label text-muted small">Fecha de Nacimiento</label>
                    <p class="form-control-plaintext fw-semibold">
                        {{ $colaborador->fecha_nacimiento
                            ? \Carbon\Carbon::parse($colaborador->fecha_nacimiento)->format('d/m/Y')
                            : '—' }}
                    </p>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label text-muted small">Género</label>
                    <p class="form-control-plaintext fw-semibold">{{ $colaborador->genero ?? '—' }}</p>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label text-muted small">Estado Civil</label>
                    <p class="form-control-plaintext fw-semibold">{{ $colaborador->estado_civil ?? '—' }}</p>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label text-muted small">Estado</label>
                    <p class="form-control-plaintext">
                        <span class="badge {{ $colaborador->estado ? 'bg-success' : 'bg-secondary' }}">
                            {{ $colaborador->estado ? 'Activo' : 'Inactivo' }}
                        </span>
                    </p>
                </div>

            </div>
        </div>
    </div>

    {{-- Contacto y Ubicación --}}
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <i class="bi bi-geo-alt me-2"></i> Contacto y Ubicación
        </div>
        <div class="card-body">
            <div class="row">

                <div class="col-md-3 mb-3">
                    <label class="form-label text-muted small">Teléfono Celular</label>
                    <p class="form-control-plaintext fw-semibold">{{ $colaborador->telefono_celular ?? '—' }}</p>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label text-muted small">Teléfono Residencial</label>
                    <p class="form-control-plaintext fw-semibold">{{ $colaborador->telefono_residencial ?? '—' }}</p>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label text-muted small">Ciudad</label>
                    <p class="form-control-plaintext fw-semibold">{{ $colaborador->ciudad ?? '—' }}</p>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label text-muted small">Barrio</label>
                    <p class="form-control-plaintext fw-semibold">{{ $colaborador->barrio ?? '—' }}</p>
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label text-muted small">Dirección</label>
                    <p class="form-control-plaintext fw-semibold">{{ $colaborador->direccion ?? '—' }}</p>
                </div>

            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════
        BLOQUE 2: Lista de Contrartos
    ═══════════════════════════════════════════ --}}
    {{-- Contrato Activo --}}
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <i class="bi bi-file-earmark-check me-2"></i> Contrato Activo
        </div>
        <div class="card-body">
            @if($contratoActivo)
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Empresa</th>
                            <th>Inicio de Contrato</th>
                            <th>Finalización de Contrato</th>
                            {{-- info adicional: --}}
                            <th>Cargo</th>
                            <th>Salario Básico</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $contratoActivo->empresa->nombre_empresa ?? '—' }}</td>
                            <td>{{ $contratoActivo->inicio_contrato
                                    ? \Carbon\Carbon::parse($contratoActivo->inicio_contrato)->format('d/m/Y')
                                    : '—' }}</td>
                            <td>{{ $contratoActivo->finalizacion_contrato
                                    ? \Carbon\Carbon::parse($contratoActivo->finalizacion_contrato)->format('d/m/Y')
                                    : '—' }}</td>
                            {{-- Datos de informacionAdicional --}}
                            <td>{{ $contratoActivo->informacionAdicional->cargo ?? '—' }}</td>
                            <td>{{ $contratoActivo->informacionAdicional->salario_basico
                                    ? '$ ' . number_format($contratoActivo->informacionAdicional->salario_basico, 2, ',', '.')
                                    : '—' }}</td>
                        </tr>
                    </tbody>
                </table>
            @else
                <p class="text-muted mb-0">Este colaborador no tiene un contrato activo.</p>
            @endif
        </div>
    </div>

    {{-- Contratos Inactivos --}}
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            <i class="bi bi-file-earmark-x me-2"></i> Historial de Contratos Inactivos
        </div>
        <div class="card-body">
            @if($contratosInactivos->isNotEmpty())
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Empresa</th>
                            <th>Inicio de Contrato</th>
                            <th>Finalización de Contrato</th>
                            <th>Cargo</th>
                            <th>Salario Básico</th>
                            <th>Motivo Inactivación</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contratosInactivos as $inactivo)
                            <tr>
                                <td>{{ $inactivo->empresa->nombre_empresa ?? '—' }}</td>
                                <td>{{ $inactivo->inicio_contrato
                                        ? \Carbon\Carbon::parse($inactivo->inicio_contrato)->format('d/m/Y')
                                        : '—' }}</td>
                                <td>{{ $inactivo->finalizacion_contrato
                                        ? \Carbon\Carbon::parse($inactivo->finalizacion_contrato)->format('d/m/Y')
                                        : '—' }}</td>
                                <td>{{ $inactivo->informacionAdicional->cargo ?? '—' }}</td>
                                <td>{{ $inactivo->informacionAdicional->salario_basico
                                        ? '$ ' . number_format($inactivo->informacionAdicional->salario_basico, 2, ',', '.')
                                        : '—' }}</td>
                                <td class="text-center">
                                    @if($inactivo->motivo_inactivacion)
                                        <span data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="{{ $inactivo->motivo_inactivacion }}"
                                            style="cursor: pointer;">
                                            <i class="bi bi-chat-left-text text-secondary fs-5"></i>
                                        </span>
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-muted mb-0">No hay contratos inactivos registrados.</p>
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
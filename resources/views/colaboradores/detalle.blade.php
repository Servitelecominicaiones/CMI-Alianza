@extends('layouts.app')

@section('title', 'Detalle del Colaborador')

@section('content')
<div class="container">

    @php
        $nombreCompleto = trim(
            ($colaborador->primer_nombre ?? '') . ' ' .
            ($colaborador->segundo_nombre ?? '') . ' ' .
            ($colaborador->primer_apellido ?? '') . ' ' .
            ($colaborador->segundo_apellido ?? '')
        );
    @endphp

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
    
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('informacion_adicional.create', $colaborador) }}" class="btn btn-primary">
            <i class="bi bi-file-earmark-plus"></i> Nuevo Contrato
        </a>
    </div>


    {{-- ══════════════════════════════════════════
        BLOQUE 2: Lista de Contratos
    ═══════════════════════════════════════════ --}}
    {{-- Contrato Activo --}}
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <i class="bi bi-file-earmark-check me-2"></i> Contrato Activo
        </div>
        <div class="card-body">
            @if($contratoActivo)
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Empresa</th>
                                <th class="text-center">Inicio de Contrato</th>
                                <th class="text-center">Finalización de Contrato</th>
                                {{-- info adicional: --}}
                                <th class="text-center">Cargo</th>
                                <th class="text-center">Salario Básico</th>
                                <th class="text-center">Documentos</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="">{{ $contratoActivo->empresa->nombre_empresa ?? '—' }}</td>
                                <td class="text-center">{{ $contratoActivo->inicio_contrato
                                        ? \Carbon\Carbon::parse($contratoActivo->inicio_contrato)->format('d/m/Y')
                                        : '—' }}</td>
                                <td class="text-center">{{ $contratoActivo->finalizacion_contrato
                                        ? \Carbon\Carbon::parse($contratoActivo->finalizacion_contrato)->format('d/m/Y')
                                        : '—' }}</td>
                                {{-- Datos de informacionAdicional --}}
                                <td class="text-center text-nowrap">{{ $contratoActivo->informacionAdicional->cargo ?? '—' }}</td>
                                <td class="text-end text-nowrap">{{ $contratoActivo->informacionAdicional->salario_basico
                                        ? '$ ' . number_format($contratoActivo->informacionAdicional->salario_basico, 2, ',', '.')
                                        : '—' }}</td>

                                {{-- Boton Ver Documentos --}}
                                <td class = "text-center">
                                    <a href="{{ route('contratos.documentos', $contratoActivo) }}" 
                                        class="btn btn-sm btn-info"
                                        title="Ver documentos del colaborador">
                                        <i class="bi bi-file-earmark-text"></i> 
                                    </a>
                                </td>

                                <td class="text-center">
                                    <div class="d-flex gap-2 justify-content-center">
                                        {{-- Ver info adicional --}}
                                        <a href="{{ route('contrato.verInfo', $contratoActivo) }}"
                                           class="btn btn-sm btn-primary"
                                           title="Ver información Contrato"
                                           data-bs-toggle="tooltip">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    
                                        {{-- Editar info adicional --}}
                                        <a href="{{ route('informacion_adicional.edit', $colaborador) }}"
                                        class="btn btn-sm btn-warning"
                                        title="Editar información Contrato"
                                        data-bs-toggle="tooltip">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        {{-- Botón subir documento - dentro de las acciones del contrato activo --}}
                                        <a href="#"
                                        class="btn btn-sm btn-success btn-subir-documento"
                                        data-url="{{ route('contratos.documentos.create', $contratoActivo) }}"
                                        title="Subir documento"
                                        data-bs-toggle="tooltip">
                                            <i class="bi bi-upload"></i>
                                        </a>
                                    
                                        {{-- Inactivar contrato --}}
                                        <button type="button"
                                                class="btn btn-sm btn-danger btn-inactivar-contrato"
                                                data-url="{{ route('contrato.modal-inactivar', $colaborador) }}"
                                                title="Inactivar contrato"
                                                data-bs-toggle="tooltip">
                                            <i class="bi bi-file-earmark-x"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
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
                <div class = "table-responsive">
                    <table class="table table-striped table-hover align-middle"style="white-space: nowrap;">
                        <thead class="table-dark">
                            <tr>
                                <th>Empresa</th>
                                <th>Inicio de Contrato</th>
                                <th>Finalización de Contrato</th>
                                <th>Cargo</th>
                                <th>Salario Básico</th>
                                <th>Documentos</th>
                                <th>Motivo Inactivación</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contratosInactivos as $inactivo)
                                <tr>
                                    <td>{{ $inactivo->empresa->nombre_empresa ?? '—' }}</td>
                                    <td class="text-center">{{ $inactivo->inicio_contrato
                                            ? \Carbon\Carbon::parse($inactivo->inicio_contrato)->format('d/m/Y')
                                            : '—' }}</td>
                                    <td class="text-center">{{ $inactivo->finalizacion_contrato
                                            ? \Carbon\Carbon::parse($inactivo->finalizacion_contrato)->format('d/m/Y')
                                            : '—' }}</td>
                                    <td>{{ $inactivo->informacionAdicional->cargo ?? '—' }}</td>
                                    <td>{{ $inactivo->informacionAdicional->salario_basico
                                            ? '$ ' . number_format($inactivo->informacionAdicional->salario_basico, 2, ',', '.')
                                            : '—' }}</td>

                                    {{-- Boton Ver Documentos --}}
                                    <td class = "text-center">
                                        <a href="{{ route('contratos.documentos', $inactivo) }}" 
                                            class="btn btn-sm btn-info"
                                            title="Ver documentos del colaborador">
                                            <i class="bi bi-file-earmark-text"></i> 
                                        </a>
                                    </td>
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
                                    <td class="text-center">
                                        <a href="{{ route('contrato.verInfo', $inactivo) }}"
                                        class="btn btn-sm btn-secondary"
                                        title="Ver información Contrato"
                                        data-bs-toggle="tooltip">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted mb-0">No hay contratos inactivos registrados.</p>
            @endif
        </div>
    </div>
</div>

{{-- Modal Inactivar Contrato --}}
<div class="modal fade" id="modalInactivarContrato" tabindex="-1">
    <div id="modalInactivarContratoContenido"></div>
</div>

{{-- Contenedor modal subir documento --}}
<div class="modal fade" id="modalSubirDocumento" tabindex="-1">
    <div id="modalSubirDocumentoContenido"></div>
</div>

@endsection

@push('scripts')

<script>

    //*Tool tip para tabla de usuarios inactivos*//
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        new bootstrap.Tooltip(el);
    });

    /*Asignacion y cargue de modal de inactivacion a traves de ajax */
    document.querySelectorAll('.btn-inactivar-contrato').forEach(btn => {
    btn.addEventListener('click', function () {
        const url = this.dataset.url;
        const contenedor = document.getElementById('modalInactivarContratoContenido');

        contenedor.innerHTML = `
            <div class="modal-dialog">
                <div class="modal-content p-4 text-center">
                    <div class="spinner-border text-danger" role="status"></div>
                    <p class="mt-2 mb-0">Cargando...</p>
                </div>
            </div>`;

        const modal = new bootstrap.Modal(document.getElementById('modalInactivarContrato'));
        modal.show();

        fetch(url)
            .then(res => res.text())
            .then(html => { contenedor.innerHTML = html; })
            .catch(() => {
                contenedor.innerHTML = `
                    <div class="modal-dialog">
                        <div class="modal-content p-4 text-center text-danger">
                            Error al cargar el modal.
                        </div>
                    </div>`;
            });
        });
    });

    /** Modal crear Documento**/
    document.querySelectorAll('.btn-subir-documento').forEach(btn => {
    btn.addEventListener('click', function (e) {
        e.preventDefault();
        const url = this.dataset.url;
        const contenedor = document.getElementById('modalSubirDocumentoContenido');

        contenedor.innerHTML = `
            <div class="modal-dialog modal-lg">
                <div class="modal-content p-4 text-center">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 mb-0">Cargando formulario...</p>
                </div>
            </div>`;

        const modal = new bootstrap.Modal(document.getElementById('modalSubirDocumento'));
        modal.show();

        fetch(url)
            .then(res => res.text())
            .then(html => {
                contenedor.innerHTML = html;

                // ✅ Ahora sí existe el elemento en el DOM
                const inputArchivo = document.getElementById('archivoModal');
                if (inputArchivo) {
                    inputArchivo.addEventListener('change', function(e) {
                        const file = e.target.files[0];
                        if (!file) return;
                        document.getElementById('preview-modal').src = URL.createObjectURL(file);
                        document.getElementById('preview-container-modal').classList.remove('d-none');
                    });
                }
            })
            .catch(() => {
                contenedor.innerHTML = `
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content p-4 text-center text-danger">
                            Error al cargar el formulario.
                        </div>
                    </div>`;
            });
        });
    });
    /** Sweet alert para errores **/
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'No se Puede Crear El Contrato',
            text: "El colaborador {{ $nombreCompleto }} ya tiene un contrato activo",
            confirmButtonText: 'Entendido'
        });
    @endif
</script>

@endpush
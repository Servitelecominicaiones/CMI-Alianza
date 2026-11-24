@extends('layouts.app')

@section('title', 'Detalle de Empresa')

@section('content')
<div class="container">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0">Detalle de Empresa</h3>
            <small class="text-muted">
                {{ $empresa->nombre_empresa }} &mdash; NIT: {{ $empresa->nit }}
            </small>
        </div>
        <a href="{{ route('empresas.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    {{-- Información General --}}
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <i class="bi bi-building me-2"></i> Información General
        </div>
        <div class="card-body">
            <div class="row">

                <div class="col-md-4 mb-3">
                    <label class="form-label text-muted small">NIT</label>
                    <p class="form-control-plaintext fw-semibold">{{ $empresa->nit ?? '—' }}</p>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label text-muted small">Nombre de la Empresa</label>
                    <p class="form-control-plaintext fw-semibold">{{ $empresa->nombre_empresa ?? '—' }}</p>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label text-muted small">Actividad</label>
                    <p class="form-control-plaintext fw-semibold">{{ $empresa->actividad ?? '—' }}</p>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label text-muted small">Teléfono</label>
                    <p class="form-control-plaintext fw-semibold">{{ $empresa->telefono ?? '—' }}</p>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label text-muted small">Estado</label>
                    <p class="form-control-plaintext">
                        <span class="badge {{ $empresa->estado ? 'bg-success' : 'bg-secondary' }}">
                            {{ $empresa->estado ? 'Activa' : 'Inactiva' }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Ubicación --}}
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <i class="bi bi-geo-alt me-2"></i> Ubicación
        </div>
        <div class="card-body">
            <div class="row">

                <div class="col-md-4 mb-3">
                    <label class="form-label text-muted small">Ciudad</label>
                    <p class="form-control-plaintext fw-semibold">{{ $empresa->ciudad ?? '—' }}</p>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label text-muted small">Barrio</label>
                    <p class="form-control-plaintext fw-semibold">{{ $empresa->barrio ?? '—' }}</p>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label text-muted small">Dirección</label>
                    <p class="form-control-plaintext fw-semibold">{{ $empresa->direccion ?? '—' }}</p>
                </div>

            </div>
        </div>
    </div>

    {{-- Botón nuevo contrato --}}
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('contratosEmpresa.create', $empresa) }}" class="btn btn-primary">
            <i class="bi bi-file-earmark-plus"></i> Nuevo Contrato
        </a>
    </div>

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
                                <th>Inicio de Contrato</th>
                                <th>Finalización de Contrato</th>
                                <th class="text-center">Documentos</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $contratoActivo->informacionAdicionalEmpresa->inicio_contrato
                                        ? \Carbon\Carbon::parse($contratoActivo->informacionAdicionalEmpresa->inicio_contrato)->format('d/m/Y')
                                        : '—' }}</td>
                                <td>{{ $contratoActivo->informacionAdicionalEmpresa->finalizacion_contrato
                                        ? \Carbon\Carbon::parse($contratoActivo->informacionAdicionalEmpresa->finalizacion_contrato)->format('d/m/Y')
                                        : '—' }}</td>
                                    
                                {{-- Ver documentos --}}
                                <td class="text-center">
                                    <a href="{{ route('contratosEmpresa.documentos', $contratoActivo) }}"
                                       class="btn btn-sm btn-info"
                                       title="Ver documentos del contrato"
                                       data-bs-toggle="tooltip">
                                        <i class="bi bi-file-earmark-text"></i>
                                        <span class="badge bg-light text-dark ms-1">{{ $contratoActivo->documentos_count }}</span>
                                    </a>
                                </td>

                                <td class="text-center">
                                    <div class="d-flex gap-2 justify-content-center">
                                        
                                        {{-- Ver informacion --}}
                                        <a href="{{ route('contratosEmpresa.verInfo', $contratoActivo) }}"
                                           class="btn btn-sm btn-primary"
                                           title="Ver información del contrato"
                                           data-bs-toggle="tooltip">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        

                                        {{-- Editar contrato --}}
                                        <a href="{{ route('contratosEmpresa.edit', $empresa) }}"
                                           class="btn btn-sm btn-warning"
                                           title="Editar contrato"
                                           data-bs-toggle="tooltip">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        {{-- Subir documento --}}
                                        <button type="button"
                                                class="btn btn-sm btn-success btn-subir-documento-empresa"
                                                data-url="{{ route('contratosEmpresa.documentos.create', $contratoActivo) }}"
                                                title="Subir documento"
                                                data-bs-toggle="tooltip">
                                            <i class="bi bi-upload"></i>
                                        </button>

                                        {{-- Inactivar contrato --}}
                                        <button type="button"
                                                class="btn btn-sm btn-danger btn-inactivar-contrato-empresa"
                                                data-url="{{ route('contratosEmpresa.modal-inactivar', $empresa) }}"
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
                <p class="text-muted mb-0">Esta empresa no tiene un contrato activo.</p>
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
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Inicio de Contrato</th>
                                <th>Finalización de Contrato</th>
                                <th class="text-center">Documentos</th>
                                <th class="text-center">Motivo Inactivación</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contratosInactivos as $inactivo)
                                <tr>
                                    <td>{{ $inactivo->informacionAdicionalEmpresa->inicio_contrato
                                            ? \Carbon\Carbon::parse($inactivo->informacionAdicionalEmpresa->inicio_contrato)->format('d/m/Y')
                                            : '—' }}</td>
                                    <td>{{ $inactivo->informacionAdicionalEmpresa->finalizacion_contrato
                                            ? \Carbon\Carbon::parse($inactivo->informacionAdicionalEmpresa->finalizacion_contrato)->format('d/m/Y')
                                            : '—' }}</td>

                                    <td class = 'text-center'>
                                        {{-- Ver documentos --}}
                                        <a href="{{ route('contratosEmpresa.documentos', $inactivo) }}"
                                           class="btn btn-sm btn-info"
                                           title="Ver documentos del contrato"
                                           data-bs-toggle="tooltip">
                                            <i class="bi bi-file-earmark-text"></i>
                                            <span class="badge bg-light text-dark ms-1">{{ $inactivo->documentos_count }}</span>
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

                                        {{-- Ver info contrato inactivo --}}
                                        <a href="{{ route('contratosEmpresa.verInfo', $inactivo) }}"
                                           class="btn btn-sm btn-secondary"
                                           title="Ver información del contrato"
                                           data-bs-toggle="tooltip">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        {{-- Subir documento --}}
                                        <button type="button"
                                                class="btn btn-sm btn-success btn-subir-documento-empresa"
                                                data-url="{{ route('contratosEmpresa.documentos.create', $inactivo) }}"
                                                title="Subir documento"
                                                data-bs-toggle="tooltip">
                                            <i class="bi bi-upload"></i>
                                        </button>
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

{{-- Modal inactivar contrato --}}
<div class="modal fade" id="modalInactivarContratoEmpresa" tabindex="-1">
    <div id="modalInactivarContratoEmpresaContenido"></div>
</div>

{{-- Modal subir documento --}}
<div class="modal fade" id="modalSubirDocumentoEmpresa" tabindex="-1">
    <div id="modalSubirDocumentoEmpresaContenido"></div>
</div>

@endsection

@push('scripts')
<script>

    /*tooltip para mostrar motivos inactivacion*/
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        new bootstrap.Tooltip(el);
    });

    /* Modal para inactivar contrato*/
    document.querySelectorAll('.btn-inactivar-contrato-empresa').forEach(btn => {
        btn.addEventListener('click', function () {
            const url = this.dataset.url;
            const contenedor = document.getElementById('modalInactivarContratoEmpresaContenido');

            contenedor.innerHTML = `
                <div class="modal-dialog">
                    <div class="modal-content p-4 text-center">
                        <div class="spinner-border text-danger" role="status"></div>
                        <p class="mt-2 mb-0">Cargando...</p>
                    </div>
                </div>`;

            const modal = new bootstrap.Modal(document.getElementById('modalInactivarContratoEmpresa'));
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

    /*sweet Alert */
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: "{{ session('error') }}",
            confirmButtonColor: '#d33'
        });
    @endif

    /* Modal para subir documento */
    document.querySelectorAll('.btn-subir-documento-empresa').forEach(btn => {
        btn.addEventListener('click', function () {
            const url = this.dataset.url;
            const contenedor = document.getElementById('modalSubirDocumentoEmpresaContenido');

            contenedor.innerHTML = `
                <div class="modal-dialog modal-lg">
                    <div class="modal-content p-4 text-center">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2 mb-0">Cargando formulario...</p>
                    </div>
                </div>`;

            const modal = new bootstrap.Modal(document.getElementById('modalSubirDocumentoEmpresa'));
            modal.show();

            fetch(url)
                .then(res => res.text())
                .then(html => {
                    contenedor.innerHTML = html;

                    // Registrar listener del input archivo una vez el modal está en el DOM
                    const inputArchivo = document.getElementById('archivoModalEmpresa');
                    if (inputArchivo) {
                        inputArchivo.addEventListener('change', function(e) {
                            const file = e.target.files[0];
                            if (!file) return;
                            document.getElementById('preview-modal-empresa').src = URL.createObjectURL(file);
                            document.getElementById('preview-container-modal-empresa').classList.remove('d-none');
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
</script>
@endpush
@extends('layouts.app')

@section('title', 'Empresas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">Gestión de Empresas</h3>

    @if(in_array('empresas.crear', session('permisos_usuario', [])))
        <a href="{{ route('empresas.create') }}" class="btn btn-primary">
            <i class="bi bi-building-add"></i> Nueva Empresa
        </a>
    @endif
</div>

<div class="card">
    <div class="card-body">
        <table id="tablaEmpresas" class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>NIT</th>
                    <th>Nombre</th>
                    <th>Actividad</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th class="text-center">Documentos</th>
                    <th>Detalle</th>
                    <th>Estado</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($empresas as $empresa)
                    <tr>
                        <td>{{ $empresa->nit }}</td>
                        <td>{{ $empresa->nombre_empresa }}</td>
                        <td>{{ $empresa->actividad }}</td>
                        <td>
                            {{ $empresa->ciudad }} - {{ $empresa->direccion }} - {{ $empresa->barrio }}
                        </td>
                        <td>{{ $empresa->telefono }}</td>

                        {{-- Boton Ver Documentos --}}
                        <td class = "text-center">
                            <a href="{{ route('empresas.documentos', $empresa) }}" 
                                class="btn btn-sm btn-info"
                                title="Ver documentos de la empresa">
                                <i class="bi bi-file-earmark-text"></i> 
                            </a>
                        </td>

                        {{-- Detalle --}}
                        <td class="text-center">
                            @if(in_array('empresas.ver', session('permisos_usuario', [])))
                                <a href="{{ route('empresas.detalle', $empresa->id_empresa) }}"
                                class="btn btn-sm btn-primary"
                                title="Ver detalle de la empresa">
                                    <i class="bi bi-eye"></i>
                                </a>
                            @endif
                        </td>

                        
                        {{-- Estado --}}
                        <td>
                            <span class="badge {{ $empresa->estado ? 'bg-success' : 'bg-secondary' }}">
                                {{ $empresa->estado ? 'Activa' : 'Inactiva' }}
                            </span>
                        </td>

                        <td class="text-center">

                            {{-- Editar --}}
                            @if(in_array('empresas.editar', session('permisos_usuario', [])))
                                <a href="{{ route('empresas.edit', $empresa->id_empresa) }}"
                                class="btn btn-sm btn-warning"
                                title="Editar empresa"> 
                                    <i class="bi bi-pencil"></i>
                                </a>
                            @endif

                            {{-- Inactivar contrato + empresa --}}
                            @if(in_array('empresas.eliminar', session('permisos_usuario', [])) && $empresa->estado)
                                <button type="button"
                                        class="btn btn-sm btn-danger btn-inactivar-contrato-empresa"
                                        data-url="{{ route('contratosEmpresa.modal-inactivar', $empresa) }}"
                                        title="Inactivar empresa">
                                    <i class="bi bi-x-circle"></i>
                                </button>
                            @endif

                            {{-- Crear nuevo contrato --}}
                            @if(in_array('empresas.crear', session('permisos_usuario', [])) && !$empresa->estado)
                                <a href="{{ route('contratosEmpresa.create', $empresa) }}"
                                   class="btn btn-sm btn-success"
                                   title="Crear nuevo contrato">
                                    <i class="bi bi-file-earmark-plus"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">
                            No hay empresas registradas
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{-- Contenedor global del modal --}}
        <div class="modal fade" id="modalInactivarContratoEmpresa" tabindex="-1">
            <div id="modalInactivarContratoEmpresaContenido"></div>
        </div>
    </div>
</div>
@endsection


@push('scripts')
<script>
document.querySelectorAll('.form-inactivar').forEach(form => {
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        Swal.fire({
            title: '¿Inactivar empresa?',
            text: 'La empresa quedará inactiva.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, inactivar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });
});

document.querySelectorAll('.form-activar').forEach(form => {
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        Swal.fire({
            title: '¿Activar empresa?',
            text: 'La empresa volverá a estar activa.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, activar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });
});


/*Scripts de data table para tabla principal*/
$(document).ready(function () {
    $('#tablaEmpresas').DataTable({
        responsive: true,
        scrollX: true,
        order: [],
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        },
        
        dom: 
            "<'row mb-3'<'col-md-6'B><'col-md-6'f>>" +
            "<'row'<'col-12'tr>>" +
            "<'row mt-3'<'col-md-6'l><'col-md-6'p>>",
            
        buttons: [{
            extend: 'excel',
            text: '<i class="bi bi-file-earmark-excel"></i> Exportar a excel',
            className: 'btn btn-success',
            title: 'Empresas del sistema',
            exportOptions: {
                columns: [0,1,2,3,4,5,8]
                }
            }
        ]
    });
});

/*Modal inactivar contrato*/
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
</script>
@endpush
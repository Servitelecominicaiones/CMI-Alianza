@extends('layouts.app')

@section('title', 'Colaboradores')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">Gestión de Colaboradores</h3>

    @if(in_array('colaboradores.crear', session('permisos_usuario', [])))
        <a href="{{ route(name: 'colaboradores.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus"></i> Nuevo colaborador
        </a>
    @endif
</div>

<div class="card">
    <div class="card-body">
        <table id ="tablaColaboradores" class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Tipo Identificación</th>
                    <th>Identificación</th>
                    <th>Nombre</th>
                    <th>Telefonó Celular</th>
                    <th class ="text-center">Documentos</th>
                    <th class="text-center">Información Adicional</th>
                    <th class="text-center">Detalle</th>
                    <th>Estado</th>
                    <th class="text-center">Acciones</th>
                    <th> Inactivar Contrato </th>  
                </tr>
            </thead>
            <tbody>
                @forelse($colaboradores as $colaborador)
                    <tr>
                        <td>{{ $colaborador -> identificacion -> tipo_identificacion }}</td>
                        <td>{{ $colaborador->numero_identificacion }}</td>

                        <td>
                            {{ $colaborador->primer_nombre }} {{ $colaborador->segundo_nombre }} {{ $colaborador->primer_apellido }} {{ $colaborador->segundo_apellido }}
                        </td>

                        <td>
                            {{ $colaborador->telefono_celular ?? 'Sin empresa' }}
                        </td>

                        
                        {{-- Boton Ver Documentos --}}
                        <td class = "text-center">
                            <a href="{{ route('colaboradores.documentos', $colaborador) }}" 
                                class="btn btn-sm btn-info"
                                title="Ver documentos del colaborador">
                                <i class="bi bi-file-earmark-text"></i> 
                            </a>
                        </td>

                        {{-- Botón mutable Info Adicional --}}
                        <td class="text-center">
                            @if($colaborador->contratoActivo())
                                {{-- Ya tiene info adicional → botón editar --}}
                                @if(in_array('colaboradores.editar', session('permisos_usuario', [])))
                                    <a href="{{ route('informacion_adicional.edit', $colaborador) }}"
                                       class="btn btn-sm btn-secondary"
                                       title="Editar información adicional">
                                        <i class="bi bi-clipboard2-check"></i>
                                    </a>
                                @endif
                            @else
                                {{-- No tiene info adicional → botón crear --}}
                                @if(in_array('colaboradores.crear', session('permisos_usuario', [])))
                                    <a href="{{ route('informacion_adicional.create', $colaborador) }}"
                                       class="btn btn-sm btn-outline-secondary"
                                       title="Agregar información adicional">
                                        <i class="bi bi-clipboard2-plus"></i>
                                    </a>
                                @endif
                            @endif
                        </td>

                        <td class = "text-center">
                            {{-- Ver Detalle --}}
                            @if(in_array('colaboradores.ver', session('permisos_usuario', [])))
                                <a href="{{ route('colaboradores.detalle', $colaborador) }}"
                                class="btn btn-sm btn-primary"
                                title="Ver detalle completo">
                                    <i class="bi bi-eye"></i>
                                </a>
                            @endif
                        </td>

                        <td>
                            <span class="badge {{ $colaborador->estado ? 'bg-success' : 'bg-secondary' }}">
                                {{ $colaborador->estado ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>

                         <td class="text-center">

                            {{-- Editar --}}
                            @if(in_array('colaboradores.editar', session('permisos_usuario', [])))
                                <a href="{{ route('colaboradores.edit', $colaborador->id_colaborador) }}"
                                    class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            @endif

                            {{-- Inactivar --}}
                            @if(
                                in_array('colaboradores.eliminar', session('permisos_usuario', []))
                                && $colaborador->estado
                            )
                                <form method="POST"
                                      action="{{ route('colaboradores.inactivar', $colaborador) }}"
                                      class="d-inline form-inactivar">
                                    @csrf
                                    @method('PUT')

                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-person-x"></i>
                                    </button>
                                </form>
                            @endif

                            {{-- Activar --}}
                            @if(
                                in_array('colaboradores.eliminar', session('permisos_usuario', []))
                                && !$colaborador->estado
                            )
                                <form method="POST"
                                      action="{{ route('colaboradores.activar', $colaborador) }}"
                                      class="d-inline form-activar">
                                    @csrf
                                    @method('PUT')

                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="bi bi-person-check"></i>
                                    </button>
                                </form>
                            @endif

                        </td>
                        <td class="text-center">
                            {{-- Botón dentro del @forelse --}}
                            @if($colaborador->contratoActivo())
                                <button type="button"
                                        class="btn btn-sm btn-danger btn-inactivar-contrato"
                                        data-url="{{ route('contrato.modal-inactivar', $colaborador) }}"
                                        title="Inactivar contrato">
                                    <i class="bi bi-file-earmark-x"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                    
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">
                            No hay colaboradores registrados
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Contenedor global del modal --}}
        <div class="modal fade" id="modalInactivarContrato" tabindex="-1">
            <div id="modalInactivarContratoContenido"></div>
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
            title: '¿Inactivar usuario?',
            text: 'El usuario no podrá acceder al sistema.',
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
            title: '¿Activar usuario?',
            text: 'El usuario podrá volver a ingresar al sistema.',
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

$(document).ready(function () {
    $('#tablaColaboradores').DataTable({
        responsive: true,
        scrollX: true,
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
            title: 'Colaboradores del sistema',
            exportOptions: {
                columns: [0,1,2,3,7]
                }
            }
        ]
    });
});

document.querySelectorAll('.btn-inactivar-contrato').forEach(btn => {
    btn.addEventListener('click', function () {
        const url = this.dataset.url;
        const contenedor = document.getElementById('modalInactivarContratoContenido');

        // Limpiar y mostrar loading
        contenedor.innerHTML = `
            <div class="modal-dialog">
                <div class="modal-content p-4 text-center">
                    <div class="spinner-border text-danger" role="status"></div>
                    <p class="mt-2 mb-0">Cargando...</p>
                </div>
            </div>`;

        // Abrir modal
        const modal = new bootstrap.Modal(document.getElementById('modalInactivarContrato'));
        modal.show();

        // Cargar contenido via AJAX
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
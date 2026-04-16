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
                    <th class="text-center">Detalle</th>
                    <th>Estado</th>
                    <th class="text-center">Acciones</th>
                    
                </tr>
            </thead>
            <tbody></tbody>
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
$(document).ready(function () {
    $('#tablaColaboradores').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("colaboradores.index") }}',
        columns: [
            { data: 'tipo_identificacion',   name: 'identificacion.tipo_identificacion' },
            { data: 'numero_identificacion', name: 'numero_identificacion' },
            { data: 'nombre_completo',       name: 'primer_nombre', searchable: true },
            { data: 'telefono_celular',      name: 'telefono_celular' },
            { 
                data: null, orderable: false, searchable: false,
                render: function(data) {
                    return `<a href="/colaboradores/${data.id_colaborador}/documentos" class="btn btn-sm btn-info">
                                <i class="bi bi-file-earmark-text"></i>
                            </a>`;
                }
            },
            { 
                data: null, orderable: false, searchable: false,
                render: function(data) {
                    return `<a href="/colaboradores/${data.id_colaborador}/detalle" class="btn btn-sm btn-primary">
                                <i class="bi bi-eye"></i>
                            </a>`;
                }
            },
            { 
                data: 'estado_badge', name: 'estado',
                render: function(data) { return data; }
            },
            { 
                data: null, orderable: false, searchable: false,
                render: function(data) {
                    let botones = `<a href="/colaboradores/${data.id_colaborador}/edit" 
                                      class="btn btn-sm btn-warning">
                                       <i class="bi bi-pencil"></i>
                                   </a>`;
                    if (data.estado) {
                        botones += `<button type="button"
                                        class="btn btn-sm btn-danger btn-inactivar-contrato ms-1"
                                        data-url="/colaboradores/${data.id_colaborador}/contrato/modal-inactivar"> 
                                        <i class="bi bi-person-x"></i>
                                    </button>`;
                    } else {
                        botones += `<a href="/colaboradores/${data.id_colaborador}/informacion-adicional/crear" class="btn btn-sm btn-success ms-1">
                                       <i class="bi bi-file-earmark-plus"></i>
                                    </a>`;
                    }
                    return botones;
                }
            },
        ],
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
            exportOptions: { columns: [0,1,2,3,6] }
        }]
    });

    $(document).on('click', '.btn-inactivar-contrato', function () {
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
</script>
@endpush
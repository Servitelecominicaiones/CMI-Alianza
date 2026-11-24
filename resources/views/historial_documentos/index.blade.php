@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <h4 class="mb-3">Historial de Documentos</h4>

    {{-- Filtros --}}
    <div class="card mb-3">
        <div class="card-body">
            <div class="row g-2 align-items-end">

                <div class="col-md-3">
                    <label class="form-label">Usuario</label>
                    <select id="filtroUsuario" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        @foreach($usuarios as $usuario)
                            <option value="{{ $usuario->id }}">{{ $usuario->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Acción</label>
                    <select id="filtroAccion" class="form-select form-select-sm">
                        <option value="">Todas</option>
                        @foreach($acciones as $accion)
                            <option value="{{ $accion }}">{{ $accion }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Desde</label>
                    <input type="date" id="filtroFechaInicio" class="form-control form-control-sm">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Hasta</label>
                    <input type="date" id="filtroFechaFin" class="form-control form-control-sm">
                </div>

                <div class="col-md-2">
                    <button id="btnExportarTodo" class="btn btn-success btn-sm w-100">
                        <i class="bi bi-file-earmark-excel"></i> Exportar todo
                    </button>
                </div>

            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle w-100" id="tablaHistorial">
            <thead class="table-dark">
                <tr>
                    <th>Fecha</th>
                    <th>Documento</th>
                    <th>Acción</th>
                    <th>Usuario</th>
                    <th>Ruta Anterior</th>
                    <th>Ruta Nueva</th>
                    <th>Ver</th>
                </tr>
            </thead>
            <tbody>
                {{-- Vacío: DataTables la llena vía AJAX --}}
            </tbody>
        </table>
    </div>

</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {

    const tabla = $('#tablaHistorial').DataTable({
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        },

        processing: true,
        serverSide: true,

        ajax: {
            url: "{{ route('historial_documentos.data') }}",
            data: function (d) {
                d.usuario_id   = $('#filtroUsuario').val();
                d.accion       = $('#filtroAccion').val();
                d.fecha_inicio = $('#filtroFechaInicio').val();
                d.fecha_fin    = $('#filtroFechaFin').val();
            }
        },

        columns: [
            { data: 'fecha' },
            { data: 'documento' },
            {
                data: 'accion',
                render: function (data) {
                    return `<span class="badge bg-info">${data}</span>`;
                }
            },
            { data: 'usuario' },
            { data: 'ruta_anterior', className: 'small text-muted' },
            { data: 'ruta_nueva', className: 'small text-muted' },
            {
                data: 'ver',
                orderable: false,
                searchable: false,
                render: function (data) {
                    return `<a href="${data}" class="btn btn-sm btn-secondary">👁</a>`;
                }
            }
        ],

        order: [[0, 'desc']],

        dom:
            "<'row mb-3'<'col-md-6'B><'col-md-6'f>>" +
            "<'row'<'col-12'tr>>" +
            "<'row mt-3'<'col-md-6'l><'col-md-6'p>>",

        buttons: [{
            extend: 'excel',
            text: '<i class="bi bi-file-earmark-excel"></i> Exportar visible',
            className: 'btn btn-success',
            title: 'Historial de Documentos',
            exportOptions: {
                columns: [0, 1, 2, 3, 4, 5]
            }
        }]
    });

    // Al cambiar cualquier filtro, recargar la tabla vía AJAX
    $('#filtroUsuario, #filtroAccion, #filtroFechaInicio, #filtroFechaFin').on('change', function () {
        tabla.ajax.reload();
    });

    // Exportar TODO (respetando filtros activos), generado en el servidor
    $('#btnExportarTodo').on('click', function () {
        const params = new URLSearchParams({
            usuario_id: $('#filtroUsuario').val() || '',
            accion: $('#filtroAccion').val() || '',
            fecha_inicio: $('#filtroFechaInicio').val() || '',
            fecha_fin: $('#filtroFechaFin').val() || ''
        });

        window.location.href = "{{ route('historial_documentos.export') }}?" + params.toString();
    });

    $('#filtroUsuario').select2({
        theme: 'bootstrap-5',
        placeholder: 'Seleccione un usuario',
        width: '100%',
        language: 'es',
        dropdownParent: $('#filtroUsuario').parent() // evita problemas de z-index si algún día está en un modal
    });

});


</script>
@endpush
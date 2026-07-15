@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-3">Historial de Documentos</h4>

    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle" id="tablaHistorial">
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
                @foreach($historial as $h)
                    <tr>
                        <td>
                            {{ $h->created_at
                                ? $h->created_at->format('Y-m-d H:i')
                                : '—'
                            }}
                        </td>


                        <td>
                            {{ $h->documento->nombre_original ?? 'Documento eliminado' }}
                        </td>

                        <td>
                            <span class="badge bg-info">
                                {{ $h->accion }}
                            </span>
                        </td>

                        <td>
                            {{ $h->usuario->nombre ?? 'Sistema' }}
                        </td>

                        <td class="small text-muted">
                            {{ $h->ruta_anterior ?? '-' }}
                        </td>

                        <td class="small text-muted">
                            {{ $h->ruta_nueva ?? '-' }}
                        </td>

                        <td>
                            <a href="{{ route('historial_documentos.show', $h) }}"
                               class="btn btn-sm btn-secondary">
                                👁
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3 d-flex justify-content-center">
        {{ $historial->links('pagination::bootstrap-5') }}
    </div>

</div>
@endsection


@push('scripts')

    <script>
    $(document).ready(function () {
        $('#tablaHistorial').DataTable({
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
                title: 'Historial de Documentos',
                exportOptions: {
                    columns: [0,1,2,3,4,5]
                    }
                }
            ]
        });
    });
    </script>

@endpush
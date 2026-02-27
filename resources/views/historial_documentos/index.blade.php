@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-3">Historial de Documentos</h4>

    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
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

    {{ $historial->links() }}

</div>
@endsection

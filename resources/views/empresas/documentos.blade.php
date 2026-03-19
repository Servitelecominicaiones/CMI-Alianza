@extends('layouts.app')

@section('title', 'Documentos de la Empresa')

@section('content')
<div class="container">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h3 class="mb-0">Documentos de la Empresa</h3>
            <p class="text-muted mb-0">
                <strong>{{ $empresa->nombre_empresa }}</strong>
            </p>
            <small class="text-muted">
                NIT: {{ $empresa->nit }} — Actividad: {{ $empresa->actividad }}
            </small>
        </div>

        <a href="{{ route('empresas.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    {{-- Card --}}
    <div class="card">
        <div class="card-header bg-dark text-white">
            <i class="bi bi-file-earmark-text me-2"></i>
            Total de documentos: {{ $documentos->count() }}
        </div>

        <div class="card-body">
            @if($documentos->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Nombre del Archivo</th>
                                <th>Categoría</th>
                                <th>Área</th>
                                <th>Año</th>
                                <th>Tamaño</th>
                                <th>Cargado por</th>
                                <th>Fecha de Carga</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documentos as $documento)
                                <tr>
                                    <td>
                                        <i class="bi bi-file-earmark-{{ $documento->extension }}"></i>
                                        {{ $documento->nombre_original }}
                                    </td>
                                    <td>{{ $documento->categoria->nombre ?? '—' }}</td>
                                    <td>{{ $documento->area->nombre ?? '—' }}</td>
                                    <td>{{ $documento->anio }}</td>
                                    <td>{{ number_format($documento->tamanio / 1024, 2) }} KB</td>
                                    <td>{{ $documento->usuario->nombre ?? 'Desconocido' }}</td>
                                    <td>{{ $documento->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="text-center">
                                        <div class="d-flex gap-2 justify-content-center">

                                            {{-- Ver --}}
                                            <a href="{{ route('documentos.preview', $documento) }}"
                                               target="_blank"
                                               class="btn btn-sm btn-primary"
                                               title="Ver documento"
                                               data-bs-toggle="tooltip">
                                                <i class="bi bi-eye"></i> 
                                            </a>

                                            @if (in_array('documentos.editar', session('permisos_usuario', [])))
                                                {{-- Editar Documento --}}
                                                <a href="{{ route('documentos.edit', $documento) }}"
                                                data-bs-toggle="tooltip"
                                                title="Editar Documento"
                                                class="btn btn-sm btn-warning">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @endif
                                            
                                            @if (in_array('documentos.eliminar', session('permisos_usuario', [])))
                                                {{-- Inactivar --}}
                                                <form method="POST" id = "formInactivar"
                                                    action="{{ route('documentos.destroy',$documento)}}"
                                                    class="d-inline"
                                                    onsubmit="return confirmarInactivacion()">
                                                    @csrf
                                                    @method("PUT")
                                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                        data-bs-toggle="tooltip" title="Inactivar Documento">
                                                        <i class="bi bi-trash"></i>
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
                    No hay documentos registrados para esta empresa.
                </p>
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
        
        document.getElementById('formInactivar').addEventListener('submit', function(e){
            e.preventDefault()

            Swal.fire({
                title: '¿Estas seguro de Inactivar este Documento?',
                text: 'Se inactivara el Documento y no aparecera en el dashboard',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, Inactivar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {

                    // Mostrar loader
                    document.getElementById('loader-overlay').style.display = 'flex';

                    this.submit();
                }
            });
        });
    </script>
@endpush
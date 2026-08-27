@extends('layouts.app')

@section('title', 'Papelera de Documentos')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">Papelera de Documentos</h3>

    <a href="{{ route('documentos.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Volver a Documentos
    </a>
</div>

<form method="GET" action="{{ route('documentos.papelera') }}" class="row g-2 mb-4">

    {{-- TEXTO --}}
    <div class="col-md-3">
        <input type="text"
               name="q"
               class="form-control"
               placeholder="Buscar documento..."
               value="{{ request('q') }}">
    </div>

    {{-- ÁREA --}}
    <div class="col-md-2">
        <select name="area_id" class="form-select">
            <option value="">Área</option>
            @foreach($areas as $area)
                <option value="{{ $area->id }}"
                    {{ request('area_id') == $area->id ? 'selected' : '' }}>
                    {{ $area->nombre }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- CATEGORÍA --}}
    <div class="col-md-2">
        <select name="categoria_id" class="form-select">
            <option value="">Categoría</option>
            @foreach($categorias as $categoria)
                <option value="{{ $categoria->id }}"
                    {{ request('categoria_id') == $categoria->id ? 'selected' : '' }}>
                    {{ $categoria->nombre }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- AÑO --}}
    <div class="col-md-1">
        <select name="anio" class="form-select">
            <option value="">Año</option>
            @foreach($anios as $anio)
                <option value="{{ $anio }}"
                    {{ request('anio') == $anio ? 'selected' : '' }}>
                    {{ $anio }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- TIPO DE PROPIETARIO --}}
    <div class="col-md-2">
        <select name="tipo_propietario" class="form-select">
            <option value="">Tipo de propietario</option>
            <option value="empresa" {{ request('tipo_propietario') === 'empresa' ? 'selected' : '' }}>Empresa</option>
            <option value="colaborador" {{ request('tipo_propietario') === 'colaborador' ? 'selected' : '' }}>Colaborador</option>
            <option value="contrato" {{ request('tipo_propietario') === 'contrato' ? 'selected' : '' }}>Contrato</option>
            <option value="contratoEmpresa" {{ request('tipo_propietario') === 'contratoEmpresa' ? 'selected' : '' }}>Contrato Empresa</option>
        </select>
    </div>

    {{-- BOTONES --}}
    <div class="col-md-2 d-grid">
        <button class="btn btn-primary">
            <i class="bi bi-search"></i> Buscar
        </button>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-hover align-middle bg-white">
        <thead>
            <tr>
                <th>Documento</th>
                <th>Tipo de propietario</th>
                <th>Propietario</th>
                <th>Fecha de subida</th>
                <th>Última edición</th>
                <th class="text-end">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($documentos as $doc)
                <tr>
                    <td>
                        <div class="text-truncate" style="max-width: 280px" title="{{ $doc->nombre_original }}">
                            {{ $doc->nombre_original }}
                        </div>
                        <small class="text-muted">
                            {{ $doc->categoria->nombre ?? '-' }} · {{ $doc->area->nombre ?? '-' }}
                        </small>
                    </td>
                    <td>{{ $doc->owner_tipo_label }}</td>
                    <td>{{ $doc->owner_label }}</td>
                    <td>{{ $doc->created_at?->format('d/m/Y H:i') }}</td>
                    <td>{{ $doc->updated_at?->format('d/m/Y H:i') }}</td>
                    <td class="text-end">

                        {{-- PREVIEW --}}
                        <a href="{{ $doc->previewUrl() }}"
                           target="_blank"
                           class="btn btn-sm btn-outline-primary"
                           title="Ver documento">
                            <i class="bi bi-eye"></i>
                        </a>

                        {{-- ACTIVAR --}}
                        @if(in_array('documentos.editar', session('permisos_usuario', [])))
                            <form method="POST"
                                  action="{{ route('documentos.activar', $doc->id) }}"
                                  class="d-inline form-activar">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-outline-success" title="Activar documento">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </button>
                            </form>
                        @endif

                        {{-- ELIMINAR PERMANENTE --}}
                        @if(in_array('documentos.eliminar', session('permisos_usuario', [])))
                            @if($doc->puedeEliminarse())
                                <form method="POST"
                                      action="{{ route('documentos.eliminarPermanente', $doc->id) }}"
                                      class="d-inline form-eliminar-permanente">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar permanentemente">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                            @else
                                <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip"
                                      title="Disponible en {{ $doc->diasRestantesParaEliminar() }} día(s)">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" disabled style="pointer-events: none;">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </span>
                            @endif
                        @endif

                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        No hay documentos inactivos.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="col-12">
    {{ $documentos->links('pagination::bootstrap-5') }}
</div>

@endsection

@push('scripts')
<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.form-activar').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Activar este documento?',
                text: 'El documento volverá a aparecer en el listado de Documentos',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, activar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    document.querySelectorAll('.form-eliminar-permanente').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Eliminar permanentemente?',
                text: 'Esta acción no se puede deshacer. El archivo se borrará del servidor.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush

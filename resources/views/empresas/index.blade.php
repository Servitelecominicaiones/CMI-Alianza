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
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>NIT</th>
                    <th>Nombre</th>
                    <th>Actividad</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th class="text-center">Colaboradores</th>
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
                            {{ $empresa->ciudad }} - {{ $empresa->direccion }} - 
                            {{ $empresa->barrio }}
                        </td>
                        <td>{{ $empresa->telefono }}</td>
                        <td class="text-center">
                            {{ $empresa->colaboradores_count }}
                        </td>

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
                                   class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            @endif

                            {{-- Inactivar --}}
                            @if(
                                in_array('empresas.eliminar', session('permisos_usuario', []))
                                && $empresa->estado
                            )
                                <form method="POST"
                                      action="{{ route('empresas.inactivar', $empresa) }}"
                                      class="d-inline form-inactivar">
                                    @csrf
                                    @method('PUT')

                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </form>
                            @endif

                            {{-- Activar --}}
                            @if(
                                in_array('empresas.eliminar', session('permisos_usuario', []))
                                && !$empresa->estado
                            )
                                <form method="POST"
                                      action="{{ route('empresas.activar', $empresa) }}"
                                      class="d-inline form-activar">
                                    @csrf
                                    @method('PUT')

                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
                                </form>
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
</script>
@endpush
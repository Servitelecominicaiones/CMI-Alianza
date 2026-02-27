@extends('layouts.app')

@section('title', 'Roles')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">Gestión de Roles</h3>

    @if(in_array('roles.crear', session('permisos_usuario', [])))
        <a href="{{ route('roles.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Rol
        </a>
    @endif
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Rol</th>
                    <th>Descripción</th>
                    <th class="text-center">Usuarios asignados</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $rol)
                    <tr>
                        <td>{{ $rol->nombre }}</td>
                        <td>{{ $rol->descripcion }}</td>

                        <td class="text-center">
                            <span class="badge bg-info">
                                {{ $rol->usuarios_count }}
                            </span>
                        </td>

                        <td class="text-center">

                            {{-- Editar --}}
                            @if(in_array('roles.editar', session('permisos_usuario', [])))
                                <a href="{{ route('roles.edit', $rol) }}"
                                   class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            @endif

                            {{-- Inactivar (NO eliminar) --}}
                            @if(
                                in_array('roles.eliminar', session('permisos_usuario', [])) &&
                                $rol->usuarios_count == 0 &&
                                $rol->nombre !== 'Administrador'
                            )
                                <form method="POST"
                                      action="{{ route('roles.inactivar', $rol) }}"
                                      class="d-inline form-inactivar-rol">
                                    @csrf
                                    @method('PUT')

                                    <button type="submit"
                                            class="btn btn-sm btn-danger"
                                            title="Inactivar rol">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </form>
                            @endif

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            No hay roles registrados
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
document.querySelectorAll('.form-inactivar-rol').forEach(form => {
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        Swal.fire({
            title: '¿Inactivar rol?',
            text: 'El rol dejará de estar disponible para asignación.',
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
</script>
@endpush

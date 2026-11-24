@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">Gestión de Usuarios</h3>

    @if(in_array('usuarios.crear', session('permisos_usuario', [])))
        <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus"></i> Nuevo usuario
        </a>
    @endif
</div>

<div class="card">
    <div class="card-body">
        <table id="tablaUsuarios" class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->nombre }}</td>
                        <td>{{ $usuario->email }}</td>
                        <td>{{ $usuario->rol->nombre ?? 'Sin rol' }}</td>
                        <td>
                            <span class="badge {{ $usuario->estado ? 'bg-success' : 'bg-secondary' }}">
                                {{ $usuario->estado ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="text-center">

                            {{-- Editar --}}
                            @if(in_array('usuarios.editar', session('permisos_usuario', [])))
                                <a href="{{ route('usuarios.edit', $usuario->id) }}"
                                   class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            @endif

                            {{-- Inactivar --}}
                            @if(
                                in_array('usuarios.eliminar', session('permisos_usuario', []))
                                && $usuario->estado
                            )
                                <form method="POST"
                                      action="{{ route('usuarios.inactivar', $usuario->id) }}"
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
                                in_array('usuarios.eliminar', session('permisos_usuario', []))
                                && !$usuario->estado
                            )
                                <form method="POST"
                                      action="{{ route('usuarios.activar', $usuario->id) }}"
                                      class="d-inline form-activar">
                                    @csrf
                                    @method('PUT')

                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="bi bi-person-check"></i>
                                    </button>
                                </form>
                            @endif

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            No hay usuarios registrados
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
    $('#tablaUsuarios').DataTable({
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
            title: 'Usuarios del sistema',
            exportOptions: {
                columns: [0,1,2,3]
                }
            }
        ]
    });
});
</script>
@endpush

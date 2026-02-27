@extends('layouts.app')

@section('title', 'Áreas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Gestión de Áreas</h3>

    @if(in_array('areas.crear', session('permisos_usuario', [])))
        <a href="{{ route('areas.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nueva Área
        </a>
    @endif
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Nombre</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($areas as $area)
                    <tr>
                        <td>{{ $area->nombre }}</td>
                        <td class="text-center">

                            @if(in_array('areas.editar', session('permisos_usuario', [])))
                                <a href="{{ route('areas.edit', $area) }}"
                                   class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            @endif

                            @if(in_array('areas.eliminar', session('permisos_usuario', [])))
                                <form method="POST"
                                      action="{{ route('areas.destroy', $area) }}"
                                      class="d-inline form-inactivar-area">
                                    @csrf
                                    @method('PUT')

                                    <button type="submit"
                                            class="btn btn-sm btn-danger">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </form>
                            @endif

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-center text-muted">
                            No hay áreas registradas
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
document.querySelectorAll('.form-inactivar-area').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        Swal.fire({
            title: '¿Inactivar área?',
            text: 'El área dejará de estar disponible.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, inactivar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc3545'
        }).then(result => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });
});
</script>
@endpush

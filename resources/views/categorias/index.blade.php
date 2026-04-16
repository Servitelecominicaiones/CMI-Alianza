@extends('layouts.app')

@section('title', 'Categorías')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Gestión de Categorías</h3>

    @if(in_array('categorias.crear', session('permisos_usuario', [])))
        <a href="{{ route('categorias.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nueva Categoría
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
                @forelse($categorias as $categoria)
                    <tr>
                        <td>{{ $categoria->nombre }}</td>
                        <td class="text-center">

                            @if(in_array('categorias.editar', session('permisos_usuario', [])))
                                <a href="{{ route('categorias.edit', $categoria) }}"
                                   class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            @endif

                            @if(in_array('categorias.eliminar', session('permisos_usuario', [])))
                                <form method="POST"
                                      action="{{ route('categorias.destroy', $categoria) }}"
                                      class="d-inline form-inactivar-categoria">
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
                            No hay categorías registradas
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <!-- PAGINACIÓN -->
        @if($categorias->count() > 0)
            <div class="d-flex justify-content-center mt-4">
                {{ $categorias->links('pagination::bootstrap-4', ['view' => 'vendor.pagination.custom']) }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.form-inactivar-categoria').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        Swal.fire({
            title: '¿Inactivar categoría?',
            text: 'La categoría dejará de estar disponible.',
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

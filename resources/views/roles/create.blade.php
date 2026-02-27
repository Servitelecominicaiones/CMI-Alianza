@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-3">Crear Rol</h4>

    <form id="formCrearRol" action="{{ route('roles.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre"
                   class="form-control"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion"
                      class="form-control"
                      rows="2"></textarea>
        </div>

        <hr>
        <h6>Permisos</h6>

        <div class="row">
            @foreach($permisos as $permiso)
                <div class="col-md-4">
                    <div class="form-check">
                        <input class="form-check-input"
                               type="checkbox"
                               name="permisos[]"
                               value="{{ $permiso }}"
                               id="permiso_{{ $loop->index }}">
                        <label class="form-check-label"
                               for="permiso_{{ $loop->index }}">
                            {{ $permiso }}
                        </label>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            <button type="button"
                    class="btn btn-primary"
                    onclick="confirmarCreacion()">
                Guardar
            </button>

            <a href="{{ route('roles.index') }}"
               class="btn btn-secondary">
                Cancelar
            </a>
        </div>
    </form>
</div>

<script>
function confirmarCreacion() {
    Swal.fire({
        title: '¿Crear rol?',
        text: 'Se guardará el rol con los permisos seleccionados',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, crear',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('formCrearRol').submit();
        }
    });
}
</script>
@endsection

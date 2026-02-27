@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-3">Editar Rol</h4>

    <form id="formEditarRol"
          action="{{ route('roles.update', $rol->id) }}"
          method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text"
                   name="nombre"
                   class="form-control"
                   value="{{ $rol->nombre }}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion"
                      class="form-control"
                      rows="2">{{ $rol->descripcion }}</textarea>
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
                               id="permiso_{{ $loop->index }}"
                               {{ in_array($permiso, $asignados) ? 'checked' : '' }}>
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
                    onclick="confirmarEdicion()">
                Actualizar
            </button>

            <a href="{{ route('roles.index') }}"
               class="btn btn-secondary">
                Cancelar
            </a>
        </div>
    </form>
</div>

<script>
function confirmarEdicion() {
    Swal.fire({
        title: '¿Actualizar rol?',
        text: 'Se guardarán los cambios realizados',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, actualizar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('formEditarRol').submit();
        }
    });
}
</script>
@endsection

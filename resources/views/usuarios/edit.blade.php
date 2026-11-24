@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('content')
<div class="container">
    <h3 class="mb-4">Editar Usuario</h3>

    <form action="{{ route('usuarios.update', $usuario) }}"
      method="POST"
      id="formEditarUsuario">

        @csrf
        @method('PUT')

        {{-- Nombre --}}
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input
                type="text"
                name="nombre"
                class="form-control @error('nombre') is-invalid @enderror"
                value="{{ old('nombre', $usuario->nombre) }}"
                required
            >
            @error('nombre')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Email --}}
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input
                type="email"
                name="email"
                class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email', $usuario->email) }}"
                required
            >
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Nueva contraseña (opcional) --}}
        <div class="mb-3">
            <label class="form-label">Nueva Contraseña (opcional)</label>
            <input
                type="password"
                name="password"
                id="password"
                class="form-control @error('password') is-invalid @enderror"
                autocomplete="new-password"
            >

            <small class="text-muted">
                Déjela vacía para mantener la contraseña actual.
                <br>
                Mínimo 8 caracteres, 1 mayúscula, 2 números y 1 carácter especial.
            </small>

            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        {{-- Rol --}}
        <div class="mb-3">
            <label class="form-label">Rol</label>
            <select
                name="rol_id"
                class="form-select @error('rol_id') is-invalid @enderror select2"
                required
            >
                @foreach($roles as $rol)
                    <option value="{{ $rol->id }}"
                        {{ old('rol_id', $usuario->rol_id) == $rol->id ? 'selected' : '' }}>
                        {{ $rol->nombre }}
                    </option>
                @endforeach
            </select>
            @error('rol_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Estado --}}
        <div class="mb-3">
            <label class="form-label">Estado</label>
            <select
                name="estado"
                class="form-select @error('estado') is-invalid @enderror"
                required
            >
                <option value="1" {{ old('estado', $usuario->estado) == 1 ? 'selected' : '' }}>
                    Activo
                </option>
                <option value="0" {{ old('estado', $usuario->estado) == 0 ? 'selected' : '' }}>
                    Inactivo
                </option>
            </select>
            @error('estado')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Actualizar
            </button>

            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
@push('scripts')
<script>
document.getElementById('formEditarUsuario').addEventListener('submit', function (e) {
    e.preventDefault();

    Swal.fire({
        title: '¿Confirmar actualización?',
        text: '¿Seguro que deseas actualizar la información del usuario?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, actualizar',
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

/* ==== Select2 ====*/
$(document).ready(function() {
    $('.select2').select2({
        placeholder: "Escribe para Buscar...",
        allowClear: true,
        width: '100%',
        theme: 'bootstrap-5'
    });
});
</script>
@endpush
@extends('layouts.app')

@section('title', 'Crear Usuario')

@section('content')
<div class="container">
    <h3 class="mb-4">Crear Usuario</h3>

    <form action="{{ route('usuarios.store') }}" method="POST">
        @csrf

        {{-- Nombre --}}
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input
                type="text"
                name="nombre"
                class="form-control @error('nombre') is-invalid @enderror"
                value="{{ old('nombre') }}"
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
                value="{{ old('email') }}"
                required
            >

            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        {{-- Password --}}
        <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input
                type="password"
                name="password"
                id="password"
                class="form-control @error('password') is-invalid @enderror"
                required
            >

            <small class="text-muted">
                Mínimo 8 caracteres, 1 mayúscula, 2 números y 1 carácter especial
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
            <select name="rol_id" class="form-select" required>
                <option value="">Seleccione un rol</option>
                @foreach($roles as $rol)
                    <option value="{{ $rol->id }}">
                        {{ $rol->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Estado --}}
        <div class="mb-3">
            <label class="form-label">Estado</label>
            <select name="estado" class="form-select" required>
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
            </select>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Guardar
            </button>

            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
        </div>
    </form>
</div>
<div id="loader-overlay">
    <div class="loader"></div>
</div>
<style>
#loader-overlay {
    position: fixed;
    inset: 0;
    background: rgba(255, 255, 255, 0.85);
    z-index: 9999;
    display: none;
    align-items: center;
    justify-content: center;
}

.loader {
    width: 60px;
    height: 60px;
    border: 6px solid #ccc;
    border-top-color: #0d6efd;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>
<script>
document.getElementById('formCrearUsuario').addEventListener('submit', function (e) {
    e.preventDefault();

    const password = document.getElementById('password').value;

    const regexMayuscula = /[A-Z]/;
    const regexNumeros = /([0-9].*[0-9])/;
    const regexEspecial = /[@$!%*?&#]/;

    if (password.length < 8 ||
        !regexMayuscula.test(password) ||
        !regexNumeros.test(password) ||
        !regexEspecial.test(password)
    ) {
        alert(
            'La contraseña debe tener:\n' +
            '- Mínimo 8 caracteres\n' +
            '- Al menos 1 letra mayúscula\n' +
            '- Al menos 2 números\n' +
            '- Al menos 1 carácter especial'
        );
        return;
    }

    if (!confirm('¿Está seguro de crear este usuario?')) {
        return;
    }

    // Mostrar loader
    document.getElementById('loader-overlay').style.display = 'flex';

    this.submit();
});
</script>

@endsection
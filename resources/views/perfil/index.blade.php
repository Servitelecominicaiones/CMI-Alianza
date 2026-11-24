@extends('layouts.app')

@section('title', 'Mi Perfil')

@section('content')
<div class="container">

    @php
        $iniciales = collect(explode(' ', trim($usuario->nombre)))
            ->filter()
            ->map(fn($palabra) => mb_strtoupper(mb_substr($palabra, 0, 1)))
            ->take(2)
            ->implode('');
    @endphp

    {{-- Encabezado --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <div class="d-flex align-items-center justify-content-center bg-primary text-white rounded-circle fw-bold"
             style="width: 64px; height: 64px; font-size: 1.5rem;">
            {{ $iniciales ?: '?' }}
        </div>
        <div>
            <h3 class="mb-1">{{ $usuario->nombre }}</h3>
            <span class="badge bg-secondary">{{ $usuario->rol->nombre ?? 'Sin rol' }}</span>
        </div>
    </div>

    <div class="row">
        {{-- Información personal --}}
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-dark text-white">
                    <i class="bi bi-person me-2"></i> Información personal
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label text-muted small">Nombre</label>
                            <p class="form-control-plaintext fw-semibold">{{ $usuario->nombre }}</p>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label text-muted small">Correo</label>
                            <p class="form-control-plaintext fw-semibold">{{ $usuario->email }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Rol</label>
                            <p class="form-control-plaintext fw-semibold">{{ $usuario->rol->nombre ?? '—' }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Estado</label>
                            <p class="form-control-plaintext">
                                @if($usuario->estado == 1)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </p>
                        </div>

                        <div class="col-12">
                            <label class="form-label text-muted small">Miembro desde</label>
                            <p class="form-control-plaintext fw-semibold">
                                {{ $usuario->created_at?->format('d/m/Y') ?? '—' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Cambiar contraseña --}}
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-dark text-white">
                    <i class="bi bi-lock me-2"></i> Cambiar contraseña
                </div>
                <div class="card-body">
                    <form action="{{ route('perfil.password') }}" method="POST" id="formCambiarPassword">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Contraseña actual</label>
                            <div class="input-group">
                                <input type="password" name="current_password" id="current_password"
                                    class="form-control @error('current_password') is-invalid @enderror"
                                    autocomplete="current-password" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="current_password">
                                    <i class="bi bi-eye"></i>
                                </button>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nueva contraseña</label>
                            <div class="input-group">
                                <input type="password" name="password" id="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    autocomplete="new-password" placeholder="Mínimo 8 caracteres" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password">
                                    <i class="bi bi-eye"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">
                                Mínimo 8 caracteres, 1 mayúscula, 2 números y 1 carácter especial.
                            </small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirmar nueva contraseña</label>
                            <div class="input-group">
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="form-control" autocomplete="new-password"
                                    placeholder="Repite la contraseña" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password_confirmation">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-key"></i> Actualizar contraseña
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('formCambiarPassword').addEventListener('submit', function (e) {
    e.preventDefault();

    Swal.fire({
        title: '¿Actualizar contraseña?',
        text: 'Se cerrará la validación de tu sesión con la nueva contraseña en el próximo inicio de sesión.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, actualizar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#0d6efd',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('loader-overlay').style.display = 'flex';
            this.submit();
        }
    });
});

document.querySelectorAll('.toggle-password').forEach(function (btn) {
    btn.addEventListener('click', function () {
        const input = document.getElementById(this.dataset.target);
        const icon = this.querySelector('i');
        const esOculto = input.type === 'password';

        input.type = esOculto ? 'text' : 'password';
        icon.classList.toggle('bi-eye');
        icon.classList.toggle('bi-eye-slash');
    });
});
</script>
@endpush

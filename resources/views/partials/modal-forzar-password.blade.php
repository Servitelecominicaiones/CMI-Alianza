<div class="modal fade" id="modalForzarPassword" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">
                    <i class="bi bi-shield-lock me-2"></i> Actualiza tu contraseña
                </h5>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    Tu contraseña tiene más de 30 días (o nunca ha sido cambiada). Por seguridad
                    debes actualizarla antes de continuar usando el sistema.
                </div>

                <form action="{{ route('perfil.password') }}" method="POST" id="formForzarPassword">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Contraseña actual</label>
                        <div class="input-group">
                            <input type="password" name="current_password" id="fp_current_password"
                                class="form-control @error('current_password') is-invalid @enderror"
                                autocomplete="current-password" required>
                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="fp_current_password">
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
                            <input type="password" name="password" id="fp_password"
                                class="form-control @error('password') is-invalid @enderror"
                                autocomplete="new-password" placeholder="Mínimo 8 caracteres" required>
                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="fp_password">
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
                            <input type="password" name="password_confirmation" id="fp_password_confirmation"
                                class="form-control" autocomplete="new-password"
                                placeholder="Repite la contraseña" required>
                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="fp_password_confirmation">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-key"></i> Actualizar contraseña
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalForzarPassword = new bootstrap.Modal(document.getElementById('modalForzarPassword'), {
        backdrop: 'static',
        keyboard: false
    });
    modalForzarPassword.show();
});

document.getElementById('formForzarPassword').addEventListener('submit', function () {
    document.getElementById('loader-overlay').style.display = 'flex';
});

document.querySelectorAll('#modalForzarPassword .toggle-password').forEach(function (btn) {
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

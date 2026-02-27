<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>ServiFile | Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #0d6efd, #0a58ca);
        }
        .login-card {
            border-radius: 1rem;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card login-card shadow-lg">
                <div class="card-body p-4">

                    <div class="text-center mb-4">
                        <h3 class="fw-bold text-primary">CMI</h3>
                        <p class="text-muted">Gestión Documental</p>
                    </div>

                    <form id="loginForm" method="POST" action="{{ route('login.submit') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Correo electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control" required autofocus>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100" id="btnLogin">
                            <span id="btnText">Ingresar</span>
                            <span id="btnSpinner" class="spinner-border spinner-border-sm d-none"></span>
                        </button>
                    </form>

                </div>
            </div>

            <p class="text-center text-white mt-3 small">© {{ date('Y') }} Servitel</p>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const form = document.getElementById('loginForm');
    const btnLogin = document.getElementById('btnLogin');
    const btnText = document.getElementById('btnText');
    const btnSpinner = document.getElementById('btnSpinner');

    form.addEventListener('submit', function () {
        btnLogin.disabled = true;
        btnText.classList.add('d-none');
        btnSpinner.classList.remove('d-none');
    });

    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Error de autenticación',
            text: '{{ $errors->first() }}'
        });
    @endif
</script>

</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CMI - Alianza')</title>

    <!-- Favicons -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="icon" href="{{ asset('images/alianzaIcono.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/alianzaIcono.png') }}">

    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f4f6f9;
        }
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: #212529;
        }
        .sidebar a {
            color: #adb5bd;
            text-decoration: none;
        }
        .sidebar a:hover,
        .sidebar .active {
            color: #fff;
            background: rgba(255,255,255,.1);
        }
        .content {
            margin-left: 260px;
        }
    </style>

    @stack('styles')
</head>
<body>

@include('partials.sidebar')

<div class="content">
    @include('partials.topbar')

    <main class="p-4">
        @yield('content')
    </main>
</div>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Select2 script -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- DataTables Buttons -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>

<!-- Export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

@if (session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Éxito',
    text: '{{ session('success') }}',
    confirmButtonColor: '#0d6efd'
});
</script>
@endif

@if(auth()->check() && auth()->user()->debeCambiarPassword() && !request()->routeIs('perfil.index'))
    @include('partials.modal-forzar-password')
@endif

{{-- @stack debe ir al final: cualquier @push declarado en vistas/partials incluidos arriba
     (como el modal de cambio de contraseña forzado) solo se vuelca aquí si @stack se
     evalúa después de que esos @push ya se ejecutaron. --}}
@stack('scripts')

<div id="loader-overlay" style="
    display:none;
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,.4);
    z-index:9999;
    align-items:center;
    justify-content:center;
">
    <div class="spinner-border text-light" role="status" style="width:4rem;height:4rem;">
        <span class="visually-hidden">Cargando...</span>
    </div>
</div>

</body>
</html>

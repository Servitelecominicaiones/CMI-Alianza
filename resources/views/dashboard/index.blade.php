@extends('layouts.app')

@section('title', 'Dashboard | CMI - Alianza')

@section('content')
<div class="row g-4">
    <div class="col-md-3">
        <a href="{{ route('documentos.index') }}" class="text-decoration-none text-dark">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-file-earmark-text fs-1 text-primary"></i>
                    <h5 class="mt-2">Documentos</h5>
                    <h3>{{ $totalDocumentos }}</h3>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-3">
        <a href="{{ route('usuarios.index') }}" class="text-decoration-none text-dark">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-people fs-1 text-success"></i>
                    <h5 class="mt-2">Usuarios</h5>
                    <h3>{{ $totalUsuarios }}</h3>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-3">
        <a href="{{route('roles.index')}}"  class="text-decoration-none text-dark">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-shield-lock fs-1 text-warning"></i>
                    <h5 class="mt-2">Roles</h5>
                    <h3>{{ $totalRoles }}</h3>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-3">
        <a href="{{ route('colaboradores.index') }}" class="text-decoration-none text-dark">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-person-badge fs-1 text-info"></i>
                    <h5 class="mt-2">Colaboradores</h5>
                    <h3>{{ $totalColaboradores }}</h3>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-3">
        <a href="{{ route('empresas.index') }}" class = "text-decoration-none text-dark">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-buildings fs-1 text-info"></i>
                    <h5 class="mt-2">Empresas</h5>
                    <h3>{{ $totalEmpresas }}</h3>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection

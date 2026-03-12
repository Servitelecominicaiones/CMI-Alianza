@extends('layouts.app')

@section('title', 'Detalle de Empresa')

@section('content')
<div class="container">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0">Detalle de Empresa</h3>
            <small class="text-muted">
                {{ $empresa->nombre_empresa }} &mdash; NIT: {{ $empresa->nit }}
            </small>
        </div>
        <a href="{{ route('empresas.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    {{-- Información General --}}
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <i class="bi bi-building me-2"></i> Información General
        </div>
        <div class="card-body">
            <div class="row">

                <div class="col-md-4 mb-3">
                    <label class="form-label text-muted small">NIT</label>
                    <p class="form-control-plaintext fw-semibold">{{ $empresa->nit ?? '—' }}</p>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label text-muted small">Nombre de la Empresa</label>
                    <p class="form-control-plaintext fw-semibold">{{ $empresa->nombre_empresa ?? '—' }}</p>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label text-muted small">Actividad</label>
                    <p class="form-control-plaintext fw-semibold">{{ $empresa->actividad ?? '—' }}</p>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label text-muted small">Teléfono</label>
                    <p class="form-control-plaintext fw-semibold">{{ $empresa->telefono ?? '—' }}</p>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label text-muted small">Estado</label>
                    <p class="form-control-plaintext">
                        <span class="badge {{ $empresa->estado ? 'bg-success' : 'bg-secondary' }}">
                            {{ $empresa->estado ? 'Activa' : 'Inactiva' }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Ubicación --}}
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <i class="bi bi-geo-alt me-2"></i> Ubicación
        </div>
        <div class="card-body">
            <div class="row">

                <div class="col-md-4 mb-3">
                    <label class="form-label text-muted small">Ciudad</label>
                    <p class="form-control-plaintext fw-semibold">{{ $empresa->ciudad ?? '—' }}</p>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label text-muted small">Barrio</label>
                    <p class="form-control-plaintext fw-semibold">{{ $empresa->barrio ?? '—' }}</p>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label text-muted small">Dirección</label>
                    <p class="form-control-plaintext fw-semibold">{{ $empresa->direccion ?? '—' }}</p>
                </div>

            </div>
        </div>
    </div>

</div>
@endsection
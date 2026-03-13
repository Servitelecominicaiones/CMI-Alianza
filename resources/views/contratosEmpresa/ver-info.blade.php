@extends('layouts.app')

@section('title', 'Información del Contrato')

@section('content')
<div class="container">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0">Información del Contrato</h3>
            <small class="text-muted">
                {{ $contratoEmpresa->empresa->nombre_empresa }}
                &mdash;
                <span class="badge {{ $contratoEmpresa->estado ? 'bg-success' : 'bg-secondary' }}">
                    {{ $contratoEmpresa->estado ? 'Activo' : 'Inactivo' }}
                </span>
            </small>
        </div>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <i class="bi bi-file-earmark-text me-2"></i> Datos del Contrato
        </div>
        <div class="card-body">
            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted small">Fecha de Inicio</label>
                    <p class="form-control-plaintext fw-semibold">
                        {{ $contratoEmpresa->informacion_adicional_empresa->inicio_contrato
                            ? \Carbon\Carbon::parse($contratoEmpresa->informacion_adicional_empresa->inicio_contrato)->format('d/m/Y')
                            : '—' }}
                    </p>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted small">Fecha de Finalización</label>
                    <p class="form-control-plaintext fw-semibold">
                        {{ $contratoEmpresa->informacion_adicional_empresa->finalizacion_contrato
                            ? \Carbon\Carbon::parse($contratoEmpresa->informacion_adicional_empresa->finalizacion_contrato)->format('d/m/Y')
                            : '—' }}
                    </p>
                </div>

                @if($contratoEmpresa->motivo_inactivacion)
                    <div class="col-md-12 mb-3">
                        <label class="form-label text-muted small">Motivo de Inactivación</label>
                        <p class="form-control-plaintext fw-semibold">
                            {{ $contratoEmpresa->motivo_inactivacion }}
                        </p>
                    </div>
                @endif

            </div>
        </div>
    </div>

</div>
@endsection
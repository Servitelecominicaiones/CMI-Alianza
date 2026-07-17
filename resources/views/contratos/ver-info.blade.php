@extends('layouts.app')

@section('title', 'Información Adicional del Contrato')

@section('content')
<div class="container">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0">Información Adicional</h3>
            <small class="text-muted">
                {{ $contrato->colaborador->primer_nombre }}
                {{ $contrato->colaborador->primer_apellido }}
                &mdash; {{ $contrato->empresa->nombre_empresa ?? 'Sin empresa' }}
                &mdash;
                <span class="badge {{ $contrato->estado ? 'bg-success' : 'bg-secondary' }}">
                    {{ $contrato->estado ? 'Activo' : 'Inactivo' }}
                </span>
            </small>
        </div>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    @if($contrato->informacionAdicional)
        @php $info = $contrato->informacionAdicional; @endphp

        {{-- Cargo --}}
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <i class="bi bi-briefcase me-2"></i> Información del Cargo
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-muted small">Cargo</label>
                        <p class="form-control-plaintext fw-semibold">{{ $info->cargo ?? '—' }}</p>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-muted small">Empresa</label>
                        <p class="form-control-plaintext fw-semibold">{{ $contrato->empresa->nombre_empresa?? '—' }}</p>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label class="form-label text-muted small">Fecha Inicial</label>
                        <p class="form-control-plaintext fw-semibold">
                            {{ $info->fecha_inicial
                                ? \Carbon\Carbon::parse($info->fecha_inicial)->format('d/m/Y')
                                : '—' }}
                        </p>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label text-muted small">Fecha Terminación</label>
                        <p class="form-control-plaintext fw-semibold">
                            {{ $info->fecha_terminacion
                                ? \Carbon\Carbon::parse($info->fecha_terminacion)->format('d/m/Y')
                                : '—' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Seguridad Social --}}
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <i class="bi bi-shield-plus me-2"></i> Seguridad Social
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-muted small">EPS</label>
                        <p class="form-control-plaintext fw-semibold">{{ $info->eps ?? '—' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-muted small">Fondo de Pensiones</label>
                        <p class="form-control-plaintext fw-semibold">{{ $info->fondo ?? '—' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-muted small">Caja de Compensación</label>
                        <p class="form-control-plaintext fw-semibold">{{ $info->caja_compensacion ?? '—' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Salario y Beneficios Base --}}
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <i class="bi bi-cash-coin me-2"></i> Salario y Beneficios Base
            </div>
            <div class="card-body">
                <div class="row">
                    @php
                        $camposSalario = [
                            'salario_basico'        => 'Salario Básico',
                            'sub_transporte'        => 'Subsidio de Transporte',
                            'medios_transporte'     => 'Medios de Transporte',
                            'factor_prestacional'   => 'Factor Prestacional',
                            'bono_servicio'         => 'Bono de Servicio',
                            'bono_salud_y_vivienda' => 'Bono Salud y Vivienda',
                            'prima_riesgo'          => 'Prima de Riesgo',
                        ];
                    @endphp
                    @foreach($camposSalario as $campo => $etiqueta)
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-muted small">{{ $etiqueta }}</label>
                            <p class="form-control-plaintext fw-semibold">
                                {{ $info->$campo !== null
                                    ? '$ ' . number_format($info->$campo, 2, ',', '.')
                                    : '—' }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Bonos y Auxilios Adicionales --}}
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <i class="bi bi-gift me-2"></i> Bonos y Auxilios Adicionales
            </div>
            <div class="card-body">
                <div class="row">
                    @php
                        $camposAdicionales = [
                            'auxilio_formacion'  => 'Auxilio de Formación',
                            'comision_fija'      => 'Comisión Fija',
                            'productividad_fija' => 'Productividad Fija',
                            'tiempo_extra_fijo'  => 'Tiempo Extra Fijo',
                            'bono_mercado'       => 'Bono Mercado',
                            'auxilio_equipo'     => 'Auxilio de Equipo',
                            'recargo_nocturno'   => 'Recargo Nocturno',
                            'trans_adicional'    => 'Transporte Adicional',
                        ];
                    @endphp
                    @foreach($camposAdicionales as $campo => $etiqueta)
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-muted small">{{ $etiqueta }}</label>
                            <p class="form-control-plaintext fw-semibold">
                                {{ $info->$campo !== null
                                    ? '$ ' . number_format($info->$campo, 2, ',', '.')
                                    : '—' }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Ubicación Física del Expediente --}}
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <i class="bi bi-archive me-2"></i> Ubicación Física del Expediente
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-muted small">Ubicación de Caja</label>
                        <p class="form-control-plaintext fw-semibold">{{ filled($info->cajaUbica) ? $info->cajaUbica : '—' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-muted small">Carpeta Inicial</label>
                        <p class="form-control-plaintext fw-semibold">{{ filled($info->carpetaIn) ? $info->carpetaIn : '—' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-muted small">Carpeta Final</label>
                        <p class="form-control-plaintext fw-semibold">{{ filled($info->carpetaFin) ? $info->carpetaFin : '—' }}</p>
                    </div>
                </div>
            </div>
        </div>

    @else
        <div class="card mb-4">
            <div class="card-body text-center text-muted py-4">
                <i class="bi bi-clipboard2-x fs-2 mb-2 d-block"></i>
                Este contrato no tiene información adicional registrada.
            </div>
        </div>
    @endif

</div>
@endsection
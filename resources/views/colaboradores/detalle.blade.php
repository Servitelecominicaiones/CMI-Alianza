@extends('layouts.app')

@section('title', 'Detalle del Colaborador')

@section('content')
<div class="container">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0">Detalle del Colaborador</h3>
            <small class="text-muted">
                {{ $colaborador->primer_nombre }}
                {{ $colaborador->segundo_nombre }}
                {{ $colaborador->primer_apellido }}
                {{ $colaborador->segundo_apellido }}
                &mdash; {{ $colaborador->numero_identificacion }}
                &mdash; {{ $contrato->empresa->nombre_empresa ?? 'Sin empresa' }}
            </small>
        </div>
        <a href="{{ route('colaboradores.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    {{-- ══════════════════════════════════════════
         BLOQUE 1: Información Básica
    ═══════════════════════════════════════════ --}}

    {{-- Empresa e Identificación --}}
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <i class="bi bi-person-vcard me-2"></i> Identificación
        </div>
        <div class="card-body">
            <div class="row">

                <div class="col-md-4 mb-3">
                    <label class="form-label text-muted small">Empresa</label>
                    <p class="form-control-plaintext fw-semibold">
                        {{ $contrato->empresa->nombre_empresa ?? '—' }}
                    </p>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label text-muted small">Tipo de Identificación</label>
                    <p class="form-control-plaintext fw-semibold">
                        {{ $colaborador->identificacion->tipo_identificacion ?? '—' }}
                    </p>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label text-muted small">Número de Identificación</label>
                    <p class="form-control-plaintext fw-semibold">
                        {{ $colaborador->numero_identificacion ?? '—' }}
                    </p>
                </div>

            </div>
        </div>
    </div>

    {{-- Datos Personales --}}
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <i class="bi bi-person me-2"></i> Datos Personales
        </div>
        <div class="card-body">
            <div class="row">

                <div class="col-md-3 mb-3">
                    <label class="form-label text-muted small">Primer Nombre</label>
                    <p class="form-control-plaintext fw-semibold">{{ $colaborador->primer_nombre ?? '—' }}</p>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label text-muted small">Segundo Nombre</label>
                    <p class="form-control-plaintext fw-semibold">{{ $colaborador->segundo_nombre ?? '—' }}</p>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label text-muted small">Primer Apellido</label>
                    <p class="form-control-plaintext fw-semibold">{{ $colaborador->primer_apellido ?? '—' }}</p>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label text-muted small">Segundo Apellido</label>
                    <p class="form-control-plaintext fw-semibold">{{ $colaborador->segundo_apellido ?? '—' }}</p>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label text-muted small">Fecha de Nacimiento</label>
                    <p class="form-control-plaintext fw-semibold">
                        {{ $colaborador->fecha_nacimiento
                            ? \Carbon\Carbon::parse($colaborador->fecha_nacimiento)->format('d/m/Y')
                            : '—' }}
                    </p>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label text-muted small">Género</label>
                    <p class="form-control-plaintext fw-semibold">{{ $colaborador->genero ?? '—' }}</p>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label text-muted small">Estado Civil</label>
                    <p class="form-control-plaintext fw-semibold">{{ $colaborador->estado_civil ?? '—' }}</p>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label text-muted small">Estado</label>
                    <p class="form-control-plaintext">
                        <span class="badge {{ $colaborador->estado ? 'bg-success' : 'bg-secondary' }}">
                            {{ $colaborador->estado ? 'Activo' : 'Inactivo' }}
                        </span>
                    </p>
                </div>

            </div>
        </div>
    </div>

    {{-- Contacto y Ubicación --}}
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <i class="bi bi-geo-alt me-2"></i> Contacto y Ubicación
        </div>
        <div class="card-body">
            <div class="row">

                <div class="col-md-3 mb-3">
                    <label class="form-label text-muted small">Teléfono Celular</label>
                    <p class="form-control-plaintext fw-semibold">{{ $colaborador->telefono_celular ?? '—' }}</p>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label text-muted small">Teléfono Residencial</label>
                    <p class="form-control-plaintext fw-semibold">{{ $colaborador->telefono_residencial ?? '—' }}</p>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label text-muted small">Ciudad</label>
                    <p class="form-control-plaintext fw-semibold">{{ $colaborador->ciudad ?? '—' }}</p>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label text-muted small">Barrio</label>
                    <p class="form-control-plaintext fw-semibold">{{ $colaborador->barrio ?? '—' }}</p>
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label text-muted small">Dirección</label>
                    <p class="form-control-plaintext fw-semibold">{{ $colaborador->direccion ?? '—' }}</p>
                </div>

            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════
         BLOQUE 2: Información Adicional
    ═══════════════════════════════════════════ --}}

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
                        <label class="form-label text-muted small">Medios de Transporte</label>
                        <p class="form-control-plaintext fw-semibold">{{ $info->medios_transporte ?? '—' }}</p>
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
                            'salario_basico'       => 'Salario Básico',
                            'sub_transporte'       => 'Subsidio de Transporte',
                            'factor_prestacional'  => 'Factor Prestacional',
                            'bono_servicio'        => 'Bono de Servicio',
                            'bono_salud_y_vivienda'=> 'Bono Salud y Vivienda',
                            'prima_riesgo'         => 'Prima de Riesgo',
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

    @else

        {{-- Sin información adicional --}}
        <div class="card mb-4">
            <div class="card-body text-center text-muted py-4">
                <i class="bi bi-clipboard2-x fs-2 mb-2 d-block"></i>
                Este colaborador aún no tiene información adicional registrada.
            </div>
        </div>

    @endif

</div>
@endsection
@extends('layouts.app')

@section('title', 'Editar Contrato')

@section('content')
<div class="container">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0">Editar Contrato</h3>
            <small class="text-muted">
                {{ $empresa->nombre_empresa }} &mdash; NIT: {{ $empresa->nit }}
            </small>
        </div>
        <a href="{{ route('empresas.detalle', $empresa) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    <form id="formEditContratoEmpresa" action="{{ route('contratosEmpresa.update', $empresa) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <i class="bi bi-pencil me-2"></i> Información del Contrato
            </div>
            <div class="card-body">
                <div class="row">

                    {{-- Inicio de contrato --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Fecha de Inicio <span class="text-danger">*</span></label>
                        <input type="date"
                               name="inicio_contrato"
                               class="form-control @error('inicio_contrato') is-invalid @enderror"
                               value="{{ old('inicio_contrato', $informacion->inicio_contrato
                                    ? \Carbon\Carbon::parse($informacion->inicio_contrato)->format('Y-m-d')
                                    : '') }}"
                               required>
                        @error('inicio_contrato')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Finalización de contrato --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Fecha de Finalización</label>
                        <input type="date"
                               name="finalizacion_contrato"
                               class="form-control @error('finalizacion_contrato') is-invalid @enderror"
                               value="{{ old('finalizacion_contrato', $informacion->finalizacion_contrato
                                    ? \Carbon\Carbon::parse($informacion->finalizacion_contrato)->format('Y-m-d')
                                    : '') }}">
                        @error('finalizacion_contrato')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>
        </div>

        <div class="d-flex gap-2 mb-4">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Actualizar
            </button>
            <a href="{{ route('empresas.detalle', $empresa) }}" class="btn btn-secondary">
                Cancelar
            </a>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('formEditContratoEmpresa').addEventListener('submit', function(e) { 

        e.preventDefault();

        Swal.fire({
            title: '¿Confirmar actualización?',
            text: '¿Seguro que deseas actualizar la información del Contrato de Empresa?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, actualizar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {

                // Mostrar loader
                document.getElementById('loader-overlay').style.display = 'flex';

                this.submit();
            }
        });

    });
</script>
@endpush
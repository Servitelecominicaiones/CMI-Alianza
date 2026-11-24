@extends('layouts.app')

@section('title', 'Crear Información Adicional')

@section('content')
<div class="container">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0">Información Adicional</h3>
            <small class="text-muted">
                {{ $colaborador->primer_nombre }}
                {{ $colaborador->segundo_nombre }}
                {{ $colaborador->primer_apellido }}
                {{ $colaborador->segundo_apellido }}
                &mdash; {{ $colaborador->numero_identificacion }}
            </small>
        </div>
        <a href="{{ route('colaboradores.detalle', $colaborador->id_colaborador) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    <form id="formInfoAdicional"
          action="{{ route('informacion_adicional.store', $colaborador) }}"
          method="POST">
        @csrf

        {{-- Formulario compartido --}}
        @include('informacionAdicional.form', ['informacion' => null])

        {{-- Botones --}}
        <div class="d-flex gap-2 mb-4">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Guardar
            </button>
            <a href="{{ route('colaboradores.detalle', $colaborador->id_colaborador) }}" class="btn btn-secondary">
                Cancelar
            </a>
        </div>

    </form>
</div>

{{-- Loader --}}
<div id="loader-overlay">
    <div class="loader"></div>
</div>

<style>
#loader-overlay {
    position: fixed;
    inset: 0;
    background: rgba(255, 255, 255, 0.85);
    z-index: 9999;
    display: none;
    align-items: center;
    justify-content: center;
}

.loader {
    width: 60px;
    height: 60px;
    border: 6px solid #ccc;
    border-top-color: #0d6efd;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>

<script>
    document.getElementById('formInfoAdicional').addEventListener('submit', function (e) {
        e.preventDefault();
        Swal.fire({
                title: '¿Subir Informacion de Contrato?',
                text: 'El Colaborador Quedara Activado con la informacion de contrato recien ingresada',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, Subir',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#6c757d',
                cancelButtonColor: '#dc3545'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
    });
</script>

@endsection
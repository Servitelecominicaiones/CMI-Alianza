@extends('layouts.app')

@section('title', 'Crear Empresa')

@section('content')
<div class="container">
    <h3 class="mb-4">Crear Empresa</h3>

    <form id="formCrearEmpresa" action="{{ route('empresas.store') }}" method="POST">
        @csrf

        {{-- NIT --}}
        <div class="mb-3">
            <label class="form-label">NIT</label>
            <input type="text"
                   name="nit"
                   class="form-control @error('nit') is-invalid @enderror"
                   value="{{ old('nit') }}"
                   required>
            @error('nit')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Nombre Empresa --}}
        <div class="mb-3">
            <label class="form-label">Nombre Empresa</label>
            <input type="text"
                   name="nombre_empresa"
                   class="form-control @error('nombre_empresa') is-invalid @enderror"
                   value="{{ old('nombre_empresa') }}"
                   required>
            @error('nombre_empresa')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Actividad --}}
        <div class="mb-3">
            <label class="form-label">Actividad</label>
            <input type="text"
                   name="actividad"
                   class="form-control @error('actividad') is-invalid @enderror"
                   value="{{ old('actividad') }}">
            @error('actividad')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Ciudad y Barrio --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Ciudad</label>
                <input type="text"
                       name="ciudad"
                       class="form-control @error('ciudad') is-invalid @enderror"
                       value="{{ old('ciudad') }}"
                       required>
                @error('ciudad')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Barrio</label>
                <input type="text"
                       name="barrio"
                       class="form-control @error('barrio') is-invalid @enderror"
                       value="{{ old('barrio') }}">
                @error('barrio')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Dirección --}}
        <div class="mb-3">
            <label class="form-label">Dirección</label>
            <input type="text"
                   name="direccion"
                   class="form-control @error('direccion') is-invalid @enderror"
                   value="{{ old('direccion') }}">
            @error('direccion')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Teléfono --}}
        <div class="mb-3">
            <label class="form-label">Teléfono</label>
            <input type="text"
                   name="telefono"
                   class="form-control @error('telefono') is-invalid @enderror"
                   value="{{ old('telefono') }}">
            @error('telefono')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Guardar
            </button>

            <a href="{{ route('empresas.index') }}" class="btn btn-secondary">
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
document.getElementById('formCrearEmpresa').addEventListener('submit', function(e){
            e.preventDefault()

            Swal.fire({
                title: '¿Estas seguro(a) de Crear esta Empresa?',
                text: 'Se incluira la informcion de esta enmpresa dentro del sistema',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, Crear',
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
</script>

@endsection

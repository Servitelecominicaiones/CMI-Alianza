@extends('layouts.app')

@section('title', 'Crear Empresa')

@section('content')
<div class="container">
    <h3 class="mb-4">Editar Empresa</h3>

    <form id="formEditarEmpresa" action="{{ route('empresas.update', $empresa->id_empresa) }}" method="POST">
        @csrf
        @method('PUT')
        {{-- NIT --}}
        <div class="mb-3">
            <label class="form-label">NIT</label>
            <input type="text"
                   name="nit"
                   class="form-control @error('nit') is-invalid @enderror"
                   value="{{ old('nit', $empresa->nit) }}"
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
                   value="{{ old('nombre_empresa', $empresa->nombre_empresa) }}"
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
                   value="{{ old('actividad', $empresa->actividad) }}">
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
                       value="{{ old('ciudad', $empresa->ciudad) }}"
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
                       value="{{ old('barrio', $empresa->barrio) }}">
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
                   value="{{ old('direccion', $empresa->direccion) }}">
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
                   value="{{ old('telefono', $empresa->telefono) }}">
            @error('telefono')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Estado --}}
        <div class="mb-3">
            <label class="form-label">Estado</label>
            <select name="estado"
                    class="form-select @error('estado') is-invalid @enderror"
                    required>
                <option value="1" {{ old('estado', $empresa->estado) == 1 ? 'selected' : '' }}>Activa</option>
                <option value="0" {{ old('estado', $empresa->estado) == 0 ? 'selected' : '' }}>Inactiva</option>
            </select>
            @error('estado')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Actualizar Empresa
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
document.getElementById('formEditarEmpresa').addEventListener('submit', function (e) {

    if (!confirm('¿Está seguro de actualizar esta empresa?')) {
        e.preventDefault();
        return;
    }

    document.getElementById('loader-overlay').style.display = 'flex';
});
</script>

@endsection

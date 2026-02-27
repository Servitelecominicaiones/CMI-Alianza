@extends('layouts.app')

@section('title', 'Crear Colaborador')

@section('content')
<div class="container">
    <h3 class="mb-4">Crear Colaborador</h3>

    <form id="formCrearColaborador" action="{{ route('colaboradores.store') }}" method="POST">
        @csrf

        {{-- Empresa --}}
        <div class="mb-3">
            <label class="form-label">Empresa</label>
            <select name="id_empresa"
                    class="form-select @error('id_empresa') is-invalid @enderror"
                    required>
                <option value="">Seleccione una empresa</option>
                @foreach($empresas as $empresa)
                    <option value="{{ $empresa->id_empresa }}"
                        {{ old('id_empresa') == $empresa->id_empresa ? 'selected' : '' }}>
                        {{ $empresa->nombre_empresa }}
                    </option>
                @endforeach
            </select>
            @error('id_empresa')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Tipo Identificación --}}
        <div class="mb-3">
            <label class="form-label">Tipo Identificación</label>
            <select name="id_tipo_identificacion"
                    class="form-select @error('id_tipo_identificacion') is-invalid @enderror"
                    required>
                <option value="">Seleccione</option>
                @foreach($identificaciones as $tipo)
                    <option value="{{ $tipo->id_identificacion }}"
                        {{ old('id_tipo_identificacion') == $tipo->id_tipo_identificacion ? 'selected' : '' }}>
                        {{ $tipo->tipo_identificacion }}
                    </option>
                @endforeach
            </select>
            @error('id_tipo_identificacion')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Número Identificación --}}
        <div class="mb-3">
            <label class="form-label">Número Identificación</label>
            <input type="text"
                   name="numero_identificacion"
                   class="form-control @error('numero_identificacion') is-invalid @enderror"
                   value="{{ old('numero_identificacion') }}"
                   required>
            @error('numero_identificacion')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Nombres --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Primer Nombre</label>
                <input type="text"
                       name="primer_nombre"
                       class="form-control @error('primer_nombre') is-invalid @enderror"
                       value="{{ old('primer_nombre') }}"
                       required>
                @error('primer_nombre')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Segundo Nombre</label>
                <input type="text"
                       name="segundo_nombre"
                       class="form-control"
                       value="{{ old('segundo_nombre') }}">
            </div>
        </div>

        {{-- Apellidos --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Primer Apellido</label>
                <input type="text"
                       name="primer_apellido"
                       class="form-control @error('primer_apellido') is-invalid @enderror"
                       value="{{ old('primer_apellido') }}"
                       required>
                @error('primer_apellido')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Segundo Apellido</label>
                <input type="text"
                       name="segundo_apellido"
                       class="form-control"
                       value="{{ old('segundo_apellido') }}">
            </div>
        </div>

        {{-- Fecha nacimiento y género --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Fecha Nacimiento</label>
                <input type="date"
                       name="fecha_nacimiento"
                       class="form-control"
                       value="{{ old('fecha_nacimiento') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Género</label>
                <select name="genero" class="form-select">
                    <option value="">Seleccione</option>
                    <option value="Masculino" {{ old('genero') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                    <option value="Femenino" {{ old('genero') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                </select>
            </div>
        </div>

        {{-- Teléfonos --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Teléfono Celular</label>
                <input type="text" name="telefono_celular" class="form-control"
                       value="{{ old('telefono_celular') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Teléfono Residencial</label>
                <input type="text" name="telefono_residencial" class="form-control"
                       value="{{ old('telefono_residencial') }}">
            </div>
        </div>

        {{-- Ciudad y Barrio --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Ciudad de Residencia</label>
                <input type="text" name="ciudad" class="form-control"
                       value="{{ old('ciudad') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Barrio de Residencia</label>
                <input type="text" name="barrio" class="form-control"
                       value="{{ old('barrio') }}">
            </div>
        </div>

        {{-- Dirección --}}
        <div class="mb-3">
            <label class="form-label">Dirección</label>
            <input type="text" name="direccion" class="form-control"
                   value="{{ old('direccion') }}">
        </div>
        
        {{-- Estado Civil --}}
        <div class="mb-3">
            <label class="form-label">Estado Civil</label>
            <select name="estado_civil" class="form-select">
                <option value="">Seleccione</option>
                <option value="Soltero(a)" {{ old('estado_civil') == 'Soltero(a)' ? 'selected' : '' }}>Soltero(a)</option>
                <option value="Casado(a)" {{ old('estado_civil') == 'Casado(a)' ? 'selected' : '' }}>Casado(a)</option>
                <option value="Divorciado(a)" {{ old('estado_civil') == 'Divorciado(a)' ? 'selected' : '' }}>Divorciado(a)</option>
                <option value="Viudo(a)" {{ old('estado_civil') == 'Viudo(a)' ? 'selected' : '' }}>Viudo(a)</option>
            </select>
        </div>

        {{-- Estado --}}
        <div class="mb-3">
            <label class="form-label">Estado</label>
            <select name="estado" class="form-select" required>
                <option value="1" {{ old('estado') == 1 ? 'selected' : '' }}>Activo</option>
                <option value="0" {{ old('estado') == 0 ? 'selected' : '' }}>Inactivo</option>
            </select>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Guardar
            </button>

            <a href="{{ route('colaboradores.index') }}" class="btn btn-secondary">
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
document.getElementById('formCrearColaborador').addEventListener('submit', function (e) {

    if (!confirm('¿Está seguro de crear este colaborador?')) {
        e.preventDefault();
        return;
    }

    document.getElementById('loader-overlay').style.display = 'flex';
});
</script>

@endsection
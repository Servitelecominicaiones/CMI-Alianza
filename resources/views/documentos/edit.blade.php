@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar documento</h2>

    <form action="{{ route('documentos.update', $documento) }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nombre original</label>
            <input type="text"
                   class="form-control"
                   value="{{ $documento->nombre_original }}"
                   disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Categoría</label>
            <select name="categoria_id" class="form-select">
                @foreach ($categorias as $cat)
                    <option value="{{ $cat->id }}"
                        {{ $documento->categoria_id == $cat->id ? 'selected' : '' }}>
                        {{ $cat->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Área</label>
            <select name="area_id" class="form-select">
                @foreach ($areas as $area)
                    <option value="{{ $area->id }}"
                        {{ $documento->area_id == $area->id ? 'selected' : '' }}>
                        {{ $area->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        
        {{-- ========== TIPO DE PROPIETARIO ========== --}}
        <div class="mb-3">
            <label class="form-label">Asignar documento a:</label>
            <select name="tipo_propietario" id="tipo_propietario" class="form-select">
                <option value="" {{ !$documento->owner_type ? 'selected' : '' }}>
                    Documento General (Sin asignar)
                </option>
                <option value="empresa" {{ $documento->owner_type === 'empresa' ? 'selected' : '' }}>
                    Empresa
                </option>
                <option value="colaborador" {{ $documento->owner_type === 'colaborador' ? 'selected' : '' }}>
                    Colaborador
                </option>
                <option value="contrato" {{ $documento->owner_type === 'contrato' ? 'selected' : '' }}>
                    Contrato
                </option>
            </select>
            <small class="text-muted">
                Opcional: Puede asignar este documento a una empresa o colaborador específico
            </small>
        </div>

        {{-- ========== SELECTOR DE EMPRESA ========== --}}
        <div class="mb-3" id="selector_empresa" style="display: {{ $documento->owner_type === 'empresa' ? 'block' : 'none' }};">
            <label class="form-label">Empresa <span class="text-danger">*</span></label>
            <select name="empresa_id" id="empresa_id" class="form-select">
                <option value="">-- Seleccione una empresa --</option>
                @foreach($empresas as $empresa)
                    <option value="{{ $empresa->id_empresa }}"
                        {{ $documento->owner_type === 'empresa' && $documento->owner_id == $empresa->id_empresa ? 'selected' : '' }}>
                        {{ $empresa->nombre_empresa }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- ========== NUEVO: SELECTOR DE COLABORADOR ========== --}}
        <div class="mb-3" id="selector_colaborador" style="display: {{ $documento->owner_type === 'colaborador' ? 'block' : 'none' }};">
            <label class="form-label">Colaborador <span class="text-danger">*</span></label>
            <select name="colaborador_id" id="colaborador_id" class="form-select">
                <option value="">-- Seleccione un colaborador --</option>
                @foreach($colaboradores as $colaborador)
                    <option value="{{ $colaborador->id_colaborador }}"
                        {{ $documento->owner_type === 'colaborador' && $documento->owner_id == $colaborador->id_colaborador ? 'selected' : '' }}>
                        {{ $colaborador->primer_nombre }} 
                        {{ $colaborador->primer_apellido }} - 
                        {{ $colaborador->numero_identificacion }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Nuevo selector de contrato --}}
        <div class="mb-3" id="selector_contrato" style="display: {{ $documento->owner_type === 'contrato' ? 'block' : 'none' }};">
            <select name="contrato_id" id="contrato_id" class="form-select">
                <option value="">-- Seleccione un contrato --</option>
                @foreach($contratos as $contrato)
                    <option value="{{ $contrato->id_contrato }}"
                        {{ $documento->owner_type === 'contrato' && $documento->owner_id == $contrato->id_contrato ? 'selected' : '' }}>
                        {{ $contrato->colaborador->primer_nombre }}
                        {{ $contrato->colaborador->primer_apellido }}
                        — {{ $contrato->empresa->nombre_empresa }}
                    </option>
                @endforeach
            </select>
        </div>


        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion"
                      class="form-control"
                      rows="3">{{ $documento->descripcion }}</textarea>
        </div>

        <button class="btn btn-primary">
            💾 Guardar cambios
        </button>

        <a href="{{ route('documentos.index') }}"
           class="btn btn-secondary">
            Cancelar
        </a>
    </form>
</div>
@endsection

@push('scripts')
<script>
// ========== MOSTRAR/OCULTAR SELECTORES DE PROPIETARIO ========== 
document.getElementById('tipo_propietario').addEventListener('change', function() {
    const tipo = this.value;
    
    const selectorEmpresa = document.getElementById('selector_empresa');
    const selectorColaborador = document.getElementById('selector_colaborador');
    const selectEmpresa = document.getElementById('empresa_id');
    const selectColaborador = document.getElementById('colaborador_id');
    const selectorContrato = document.getElementById('selector_contrato');
    const selectContrato   = document.getElementById('contrato_id');

    // Ocultar ambos selectores
    selectorEmpresa.style.display = 'none';
    selectorColaborador.style.display = 'none';
    selectorContrato.style.display = 'none'

    // Limpiar selecciones previas
    selectEmpresa.value = '';
    selectColaborador.value = '';
    selectContrato.value = '';
    
    // Remover atributo required de los elementos
    selectEmpresa.removeAttribute('required');
    selectColaborador.removeAttribute('required');
    selectContrato.removeAttribute('required');

    // Mostrar el selector correspondiente y hacerlo requerido
    if (tipo === 'empresa') {
        selectorEmpresa.style.display = 'block';
        selectEmpresa.setAttribute('required', 'required');
    } else if (tipo === 'colaborador') {
        selectorColaborador.style.display = 'block';
        selectColaborador.setAttribute('required', 'required');
    } else if (tipo === 'contrato') {
        selectorContrato.style.display = 'block';
        selectContrato.setAttribute('required', 'required');
    }
});
</script>
@endpush
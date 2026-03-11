@extends('layouts.app')

@section('title', 'Nuevo Documento')

@section('content')

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Subir nuevo documento</h5>
    </div>

    <div class="card-body">
        <form action="{{ route('documentos.store') }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf

            {{-- Archivo --}}
            <div class="mb-3">
                <label class="form-label">Documento</label>
                <input type="file"
                       name="archivo"
                       id="archivo"
                       class="form-control"
                       required>
            </div>

            {{-- Vista previa --}}
            <div class="mb-3 d-none" id="preview-container">
                <label class="form-label">Vista previa</label>
                <iframe id="preview"
                        class="w-100 border rounded"
                        style="height:300px;"></iframe>
            </div>

            {{-- Área --}}
            <div class="mb-3">
                <label class="form-label">Área</label>
                <select name="area_id" class="form-select" required>
                    <option value="">Seleccione</option>
                    @foreach($areas as $area)
                        <option value="{{ $area->id }}">
                            {{ $area->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Categoría --}}
            <div class="mb-3">
                <label class="form-label">Categoría</label>
                <select name="categoria_id" class="form-select" required>
                    <option value="">Seleccione</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}">
                            {{ $categoria->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Tipo De Propietario --}}
            <div class="mb-3">
                <label class="form-label">Asignar documento a:</label>
                <select name="tipo_propietario" id="tipo_propietario" class="form-select">
                    <option value="">Documento General (Sin asignar)</option>
                    <option value="empresa">Empresa</option>
                    <option value="colaborador">Colaborador</option>
                    <option value="contrato">Contrato</option>
                </select>
                <small class="text-muted">
                    Opcional: Puede asignar este documento a una empresa o colaborador específico
                </small>
            </div>

            {{-- Selector Empresa --}}
            <div class="mb-3" id="selector_empresa" style="display: none;">
                <label class="form-label">Empresa <span class="text-danger">*</span></label>
                <select name="empresa_id" id="empresa_id" class="form-select">
                    <option value="">-- Seleccione una empresa --</option>
                    @foreach($empresas as $empresa)
                        <option value="{{ $empresa->id_empresa }}">
                            {{ $empresa->nombre_empresa }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Selector Colaborador --}}
            <div class="mb-3" id="selector_colaborador" style="display: none;">
                <label class="form-label">Colaborador <span class="text-danger">*</span></label>
                <select name="colaborador_id" id="colaborador_id" class="form-select">
                    <option value="">-- Seleccione un colaborador --</option>
                    @foreach($colaboradores as $colaborador)
                        <option value="{{ $colaborador->id_colaborador }}">
                            {{ $colaborador->primer_nombre }} 
                            {{ $colaborador->primer_apellido }} - 
                            {{ $colaborador->numero_identificacion }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Nuevo selector de contrato --}}
            <div class="mb-3" id="selector_contrato" style="display: none;">
                <label class="form-label">Contrato <span class="text-danger">*</span></label>
                <select name="contrato_id" id="contrato_id" class="form-select">
                    <option value="">-- Seleccione un contrato --</option>
                    @foreach($contratos as $contrato)
                        <option value="{{ $contrato->id_contrato }}">
                            {{ $contrato->colaborador->primer_nombre }}
                            {{ $contrato->colaborador->primer_apellido }}
                            — {{ $contrato->empresa->nombre_empresa }}
                            — {{ $contrato -> inicio_contrato }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Descripción --}}
            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea name="descripcion"
                          class="form-control"
                          rows="3"></textarea>
            </div>

            <div class="text-end">
                <a href="{{ route('documentos.index') }}"
                   class="btn btn-secondary">
                    Cancelar
                </a>

                <button class="btn btn-primary">
                    Subir documento
                </button>
            </div>

        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.getElementById('archivo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    const preview = document.getElementById('preview');
    const container = document.getElementById('preview-container');

    preview.src = URL.createObjectURL(file);
    container.classList.remove('d-none');
});

//Mosrar/Ocultar Selectores de propietario

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
    selectorContrato.style.display = 'none';
    
    // Limpiar selecciones previas
    selectEmpresa.value = '';
    selectColaborador.value = '';
    selectContrato.value = '';

    // Remover atributo required de ambos
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
    }else if (tipo === 'contrato') {
        selectorContrato.style.display = 'block';
        selectContrato.setAttribute('required', 'required');
    }
});

</script>
@endpush

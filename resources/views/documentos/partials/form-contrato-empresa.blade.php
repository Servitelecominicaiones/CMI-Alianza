<div class="modal-dialog modal-lg">
    <div class="modal-content">

        <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">
                <i class="bi bi-file-earmark-plus me-2"></i> Subir Documento
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <form action="{{ route('documentos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Campos ocultos --}}
            <input type="hidden" name="tipo_propietario" value="contratoEmpresa">
            <input type="hidden" name="contrato_empresa_id" value="{{ $contratoEmpresa->id_contrato_empresa }}">
            <input type="hidden" name="redirect_empresa" value="{{ $contratoEmpresa->empresa->id_empresa }}">

            <div class="modal-body">

                {{-- Info del contrato --}}
                <div class="alert alert-info mb-3">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Empresa:</strong> {{ $contratoEmpresa->empresa->nombre_empresa }}
                </div>

                {{-- Archivo --}}
                <div class="mb-3">
                    <label class="form-label">Documento <span class="text-danger">*</span></label>
                    <input type="file" name="archivo" id="archivoModalEmpresa" class="form-control" required>
                </div>

                {{-- Vista previa --}}
                <div class="mb-3 d-none" id="preview-container-modal-empresa">
                    <label class="form-label">Vista previa</label>
                    <iframe id="preview-modal-empresa" class="w-100 border rounded" style="height:200px;"></iframe>
                </div>

                {{-- Área --}}
                <div class="mb-3">
                    <label class="form-label">Área <span class="text-danger">*</span></label>
                    <select name="area_id" class="form-select" required>
                        <option value="">Seleccione</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Categoría --}}
                <div class="mb-3">
                    <label class="form-label">Categoría <span class="text-danger">*</span></label>
                    <select name="categoria_id" class="form-select" required>
                        <option value="">Seleccione</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Descripción --}}
                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="2"></textarea>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-upload me-1"></i> Subir documento
                </button>
            </div>

        </form>
    </div>
</div>
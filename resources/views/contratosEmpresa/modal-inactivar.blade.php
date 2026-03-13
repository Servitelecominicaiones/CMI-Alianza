<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header bg-danger text-white">
            <h5 class="modal-title">
                <i class="bi bi-x-circle me-2"></i> Inactivar Contrato
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <form method="POST" action="{{ route('contratosEmpresa.inactivar', $empresa) }}">
            @csrf
            @method('PUT')

            <div class="modal-body">
                <p class="mb-1">
                    <strong>Empresa:</strong> {{ $empresa->nombre_empresa }}
                </p>
                <p class="mb-1">
                    <strong>Inicio de contrato:</strong>
                    {{ $contrato->informacionAdicionalEmpresa->inicio_contrato
                        ? \Carbon\Carbon::parse($contrato->informacionAdicionalEmpresa->inicio_contrato)->format('d/m/Y')
                        : '—' }}
                </p>
                <p class="mb-3">
                    <strong>Finalización de contrato:</strong>
                    {{ $contrato->informacionAdicionalEmpresa->finalizacion_contrato
                        ? \Carbon\Carbon::parse($contrato->informacionAdicionalEmpresa->finalizacion_contrato)->format('d/m/Y')
                        : '—' }}
                </p>

                <hr>

                <div class="mb-3">
                    <label class="form-label">
                        Motivo de inactivación <span class="text-danger">*</span>
                    </label>
                    <textarea name="motivo"
                              class="form-control"
                              rows="3"
                              placeholder="Describa el motivo..."
                              required></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-x-circle me-1"></i> Inactivar
                </button>
            </div>
        </form>
    </div>
</div>
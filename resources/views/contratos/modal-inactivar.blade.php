<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header bg-danger text-white">
            <h5 class="modal-title">
                <i class="bi bi-file-earmark-x me-2"></i> Inactivar Contrato
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <form method="POST" action="{{ route('contrato.inactivar', $colaborador) }}">
            @csrf
            @method('PUT')

            <div class="modal-body">
                
                <p class="text-center">
                    <strong> ¿Esta Seguro de inactivar el siguente contrato?</strong>
                </p>
                {{-- Info del colaborador --}}
                <p class="mb-1">
                    <strong>Colaborador:</strong>
                    {{ $colaborador->primer_nombre }} {{ $colaborador->primer_apellido }}
                </p>

                <p class="mb-1">
                    <strong>Identificación:</strong>
                    {{ $colaborador->numero_identificacion }}
                </p>

                {{-- Fechas del contrato --}}
                <p class="mb-1">
                    <strong>Fecha inicio:</strong>
                    {{ $contrato->informacionAdicional->fecha_inicial
                        ? \Carbon\Carbon::parse($contrato->informacionAdicional->fecha_inicial)->format('d/m/Y')
                        : '—' }}
                </p>
                <p class="mb-3">
                    <strong>Fecha terminación:</strong>
                    {{ $contrato->informacionAdicional->fecha_terminacion
                        ? \Carbon\Carbon::parse($contrato->informacionAdicional->fecha_terminacion)->format('d/m/Y')
                        : '—' }}
                </p>

                <hr>

                {{-- Motivo --}}
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
                    <i class="bi bi-file-earmark-x me-1"></i> Inactivar contrato
                </button>
            </div>
        </form>
    </div>
</div>
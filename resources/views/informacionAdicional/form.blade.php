{{--
    Partial: form.blade.php
    Variables esperadas:
        - $colaborador  → instancia del colaborador
        - $informacion  → instancia de InformacionAdicionalColaborador (puede ser null en create)
--}}

{{-- Información del Cargo --}}
<div class="card mb-4">
    <div class="card-header bg-dark text-white">
        <i class="bi bi-briefcase me-2"></i> Información del Cargo
    </div>
    <div class="card-body">
        <div class="row">

            {{-- Cargo --}}
            <div class="col-md-6 mb-3">
                <label class="form-label">Cargo</label>
                <input type="text"
                       name="cargo"
                       class="form-control @error('cargo') is-invalid @enderror"
                       value="{{ old('cargo', $informacion->cargo ?? '') }}">
                @error('cargo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Empresa --}}
            <div class="col-md-6 mb-3">
            <label class="form-label">Empresa</label>
            <select name="id_empresa"
                    id="select-empresa"
                    class="form-select select2-empresa @error('id_empresa') is-invalid @enderror"
                    required>
                <option value="">Seleccione una empresa</option>
                @foreach($empresas as $empresa)
                    <option value="{{ $empresa->id_empresa }}"
                        {{ old('id_empresa', $contrato->id_empresa ?? null) == $empresa->id_empresa ? 'selected' : ''  }}>
                        {{ $empresa->nombre_empresa }}
                    </option>
                @endforeach
            </select>
            @error('id_empresa')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            </div>

            {{-- Fecha Inicial --}}
            <div class="col-md-6 mb-3">
                <label class="form-label">Fecha Inicial</label>
                <input type="date"
                       name="fecha_inicial"
                       class="form-control @error('fecha_inicial') is-invalid @enderror"
                       value="{{ old('fecha_inicial', isset($informacion->fecha_inicial) ? \Carbon\Carbon::parse($informacion->fecha_inicial)->format('Y-m-d') : '') }}">
                @error('fecha_inicial')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Fecha Terminación --}}
            <div class="col-md-6 mb-3">
                <label class="form-label">Fecha Terminación</label>
                <input type="date"
                       name="fecha_terminacion"
                       class="form-control @error('fecha_terminacion') is-invalid @enderror"
                       value="{{ old('fecha_terminacion', isset($informacion->fecha_terminacion) ? \Carbon\Carbon::parse($informacion->fecha_terminacion)->format('Y-m-d') : '') }}">
                @error('fecha_terminacion')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

        </div>
    </div>
</div>

{{-- Seguridad Social --}}
<div class="card mb-4">
    <div class="card-header bg-dark text-white">
        <i class="bi bi-shield-plus me-2"></i> Seguridad Social
    </div>
    <div class="card-body">
        <div class="row">

            {{-- EPS --}}
            <div class="col-md-4 mb-3">
                <label class="form-label">EPS</label>
                <input type="text"
                       name="eps"
                       class="form-control @error('eps') is-invalid @enderror"
                       value="{{ old('eps', $informacion->eps ?? '') }}">
                @error('eps')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Fondo --}}
            <div class="col-md-4 mb-3">
                <label class="form-label">Fondo de Pensiones</label>
                <input type="text"
                       name="fondo"
                       class="form-control @error('fondo') is-invalid @enderror"
                       value="{{ old('fondo', $informacion->fondo ?? '') }}">
                @error('fondo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Caja de Compensación --}}
            <div class="col-md-4 mb-3">
                <label class="form-label">Caja de Compensación</label>
                <input type="text"
                       name="caja_compensacion"
                       class="form-control @error('caja_compensacion') is-invalid @enderror"
                       value="{{ old('caja_compensacion', $informacion->caja_compensacion ?? '') }}">
                @error('caja_compensacion')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

        </div>
    </div>
</div>

{{-- Salario y Beneficios Base --}}
<div class="card mb-4">
    <div class="card-header bg-dark text-white">
        <i class="bi bi-cash-coin me-2"></i> Salario y Beneficios Base
    </div>
    <div class="card-body">
        <div class="row">

            {{-- Salario Básico --}}
            <div class="col-md-4 mb-3">
                <label class="form-label">Salario Básico</label>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" step="0.01"
                           name="salario_basico"
                           class="form-control @error('salario_basico') is-invalid @enderror"
                           value="{{ old('salario_basico', $informacion->salario_basico ?? '') }}">
                    @error('salario_basico')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Subsidio de Transporte --}}
            <div class="col-md-4 mb-3">
                <label class="form-label">Subsidio de Transporte</label>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" step="0.01"
                           name="sub_transporte"
                           class="form-control @error('sub_transporte') is-invalid @enderror"
                           value="{{ old('sub_transporte', $informacion->sub_transporte ?? '') }}">
                    @error('sub_transporte')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Medios de Transporte --}}
            <div class="col-md-4 mb-3">
                <label class="form-label">Medios de Transporte</label>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number"
                        name="medios_transporte"
                        class="form-control @error('medios_transporte') is-invalid @enderror"
                        value="{{ old('medios_transporte', $informacion->medios_transporte ?? '') }}">
                    @error('medios_transporte')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Factor Prestacional --}}
            <div class="col-md-4 mb-3">
                <label class="form-label">Factor Prestacional</label>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" step="0.01"
                        name="factor_prestacional"
                        class="form-control @error('factor_prestacional') is-invalid @enderror"
                        value="{{ old('factor_prestacional', $informacion->factor_prestacional ?? '') }}">
                    @error('factor_prestacional')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Bono de Servicio --}}
            <div class="col-md-4 mb-3">
                <label class="form-label">Bono de Servicio</label>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" step="0.01"
                        name="bono_servicio"
                        class="form-control @error('bono_servicio') is-invalid @enderror"
                        value="{{ old('bono_servicio', $informacion->bono_servicio ?? '') }}">
                    @error('bono_servicio')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Bono Salud y Vivienda --}}
            <div class="col-md-4 mb-3">
                <label class="form-label">Bono Salud y Vivienda</label>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" step="0.01"
                        name="bono_salud_y_vivienda"
                        class="form-control @error('bono_salud_y_vivienda') is-invalid @enderror"
                        value="{{ old('bono_salud_y_vivienda', $informacion->bono_salud_y_vivienda ?? '') }}">
                    @error('bono_salud_y_vivienda')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Prima de Riesgo --}}
            <div class="col-md-4 mb-3">
                <label class="form-label">Prima de Riesgo</label>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" step="0.01"
                        name="prima_riesgo"
                        class="form-control @error('prima_riesgo') is-invalid @enderror"
                        value="{{ old('prima_riesgo', $informacion->prima_riesgo ?? '') }}">
                    @error('prima_riesgo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Bonos y Auxilios Adicionales --}}
<div class="card mb-4">
    <div class="card-header bg-dark text-white">
        <i class="bi bi-gift me-2"></i> Bonos y Auxilios Adicionales
    </div>
    <div class="card-body">
        <div class="row">

            {{-- Auxilio Formación --}}
            <div class="col-md-4 mb-3">
                <label class="form-label">Auxilio de Formación</label>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" step="0.01"
                           name="auxilio_formacion"
                           class="form-control @error('auxilio_formacion') is-invalid @enderror"
                           value="{{ old('auxilio_formacion', $informacion->auxilio_formacion ?? '') }}">
                    @error('auxilio_formacion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Comisión Fija --}}
            <div class="col-md-4 mb-3">
                <label class="form-label">Comisión Fija</label>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" step="0.01"
                           name="comision_fija"
                           class="form-control @error('comision_fija') is-invalid @enderror"
                           value="{{ old('comision_fija', $informacion->comision_fija ?? '') }}">
                    @error('comision_fija')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Productividad Fija --}}
            <div class="col-md-4 mb-3">
                <label class="form-label">Productividad Fija</label>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" step="0.01"
                           name="productividad_fija"
                           class="form-control @error('productividad_fija') is-invalid @enderror"
                           value="{{ old('productividad_fija', $informacion->productividad_fija ?? '') }}">
                    @error('productividad_fija')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Tiempo Extra Fijo --}}
            <div class="col-md-4 mb-3">
                <label class="form-label">Tiempo Extra Fijo</label>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" step="0.01"
                           name="tiempo_extra_fijo"
                           class="form-control @error('tiempo_extra_fijo') is-invalid @enderror"
                           value="{{ old('tiempo_extra_fijo', $informacion->tiempo_extra_fijo ?? '') }}">
                    @error('tiempo_extra_fijo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Bono Mercado --}}
            <div class="col-md-4 mb-3">
                <label class="form-label">Bono Mercado</label>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" step="0.01"
                           name="bono_mercado"
                           class="form-control @error('bono_mercado') is-invalid @enderror"
                           value="{{ old('bono_mercado', $informacion->bono_mercado ?? '') }}">
                    @error('bono_mercado')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Auxilio Equipo --}}
            <div class="col-md-4 mb-3">
                <label class="form-label">Auxilio de Equipo</label>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" step="0.01"
                           name="auxilio_equipo"
                           class="form-control @error('auxilio_equipo') is-invalid @enderror"
                           value="{{ old('auxilio_equipo', $informacion->auxilio_equipo ?? '') }}">
                    @error('auxilio_equipo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Recargo Nocturno --}}
            <div class="col-md-4 mb-3">
                <label class="form-label">Recargo Nocturno</label>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" step="0.01"
                           name="recargo_nocturno"
                           class="form-control @error('recargo_nocturno') is-invalid @enderror"
                           value="{{ old('recargo_nocturno', $informacion->recargo_nocturno ?? '') }}">
                    @error('recargo_nocturno')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Transporte Adicional --}}
            <div class="col-md-4 mb-3">
                <label class="form-label">Transporte Adicional</label>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" step="0.01"
                           name="trans_adicional"
                           class="form-control @error('trans_adicional') is-invalid @enderror"
                           value="{{ old('trans_adicional', $informacion->trans_adicional ?? '') }}">
                    @error('trans_adicional')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function () {
        $('#select-empresa').select2({
            theme: 'bootstrap-5',
            placeholder: 'Seleccione una empresa',
            width: '100%',
            language: 'es',
            dropdownParent: $('#select-empresa').parent() // evita problemas de z-index si algún día está en un modal
        });
    });
</script>
@endpush
@extends('layouts.app')

@section('title', 'Editar Área')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Editar Área</h5>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('areas.update', $area) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text"
                       name="nombre"
                       value="{{ $area->nombre }}"
                       class="form-control"
                       required>
            </div>

            <div class="text-end">
                <a href="{{ route('areas.index') }}" class="btn btn-secondary">
                    Cancelar
                </a>
                <button class="btn btn-primary">
                    Actualizar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

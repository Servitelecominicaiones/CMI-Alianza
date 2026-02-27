@extends('layouts.app')

@section('title', 'Editar Categoría')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Editar Categoría</h5>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('categorias.update', $categoria) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text"
                       name="nombre"
                       value="{{ $categoria->nombre }}"
                       class="form-control"
                       required>
            </div>

            <div class="text-end">
                <a href="{{ route('categorias.index') }}" class="btn btn-secondary">
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

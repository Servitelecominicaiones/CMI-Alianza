@extends('layouts.app')

@section('title', 'Nueva Categoría')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Nueva Categoría</h5>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('categorias.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text"
                       name="nombre"
                       class="form-control"
                       required>
            </div>

            <div class="text-end">
                <a href="{{ route('categorias.index') }}" class="btn btn-secondary">
                    Cancelar
                </a>
                <button class="btn btn-primary">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

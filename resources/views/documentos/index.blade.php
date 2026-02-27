@extends('layouts.app')

@section('title', 'Documentos')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">Gestión de Documentos</h3>

    @if(in_array('documentos.crear', session('permisos_usuario', [])))
        <a href="{{ route('documentos.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Documento
        </a>
    @endif
</div>

<form method="GET" action="{{ route('documentos.index') }}" class="row g-2 mb-4">

    {{-- TEXTO --}}
    <div class="col-md-3">
        <input type="text"
               name="q"
               class="form-control"
               placeholder="Buscar documento..."
               value="{{ request('q') }}">
    </div>

    {{-- ÁREA --}}
    <div class="col-md-2">
        <select name="area_id" class="form-select">
            <option value="">Área</option>
            @foreach($areas as $area)
                <option value="{{ $area->id }}"
                    {{ request('area_id') == $area->id ? 'selected' : '' }}>
                    {{ $area->nombre }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- CATEGORÍA --}}
    <div class="col-md-2">
        <select name="categoria_id" class="form-select">
            <option value="">Categoría</option>
            @foreach($categorias as $categoria)
                <option value="{{ $categoria->id }}"
                    {{ request('categoria_id') == $categoria->id ? 'selected' : '' }}>
                    {{ $categoria->nombre }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- AÑO --}}
    <div class="col-md-2">
        <select name="anio" class="form-select">
            <option value="">Año</option>
            @foreach($anios as $anio)
                <option value="{{ $anio }}"
                    {{ request('anio') == $anio ? 'selected' : '' }}>
                    {{ $anio }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- ESTADO --}}
    <div class="col-md-2">
        <select name="estado" class="form-select">
            <option value="">Estado</option>
            <option value="1" {{ request('estado') === '1' ? 'selected' : '' }}>Activo</option>
            <option value="0" {{ request('estado') === '0' ? 'selected' : '' }}>Inactivo</option>
        </select>
    </div>

    {{-- BOTONES --}}
    <div class="col-md-1 d-grid">
        <button class="btn btn-primary">
            <i class="bi bi-search"></i>
        </button>
    </div>
</form>

<div class="row" id="contenedor-documentos">
    @include('documentos.partials.cards', ['documentos' => $documentos])
</div>

<div id="loader" class="text-center my-4 d-none">
    <span>Cargando...</span>
</div>

@if($documentos->hasMorePages())
<button id="btn-cargar-mas"
        data-url="{{ $documentos->nextPageUrl() }}"
        class="btn btn-outline-primary mt-3">
    Cargar más
</button>
@endif


@endsection

@push('scripts')
<script src="{{ asset('js/documentos/index.js') }}"></script>
@endpush

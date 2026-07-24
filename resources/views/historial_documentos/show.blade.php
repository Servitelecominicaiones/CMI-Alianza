@extends('layouts.app')

@section('content')
<div class="container">

    <h4>Detalle del Historial</h4>

    <div class="card mt-3">
        <div class="card-body">

            <p><strong>Fecha:</strong> {{ $historialDocumento->fecha }}</p>
            <p><strong>Acción:</strong> {{ $historialDocumento->accion }}</p>
            <p><strong>Usuario:</strong> {{ $historialDocumento->usuario->nombre ?? 'Sistema' }}</p>
            <p><strong>Documento:</strong> {{ $historialDocumento->documento->nombre_original ?? 'Eliminado' }}</p>

            <hr>

            <p><strong>Ruta Anterior:</strong></p>
            <pre>{{ $historialDocumento->ruta_anterior ?? '-' }}</pre>

            <p><strong>Ruta Nueva:</strong></p>
            <pre>{{ $historialDocumento->ruta_nueva ?? '-' }}</pre>

            <a href="{{ route('historial_documentos.index') }}"
            class="btn btn-secondary mt-3">
                ⬅ Volver
            </a>

        </div>
    </div>

</div>
@endsection

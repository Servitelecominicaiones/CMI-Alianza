@extends('layouts.app')

@section('title','Usuarios')

@section('content')
<div class="card shadow-sm">
    <div class="card-body">

        <h4 class="mb-4">
            {{ isset($usuario) ? 'Editar usuario' : 'Nuevo usuario' }}
        </h4>

        <form method="POST"
              action="{{ isset($usuario) ? route('usuarios.update',$usuario->id) : route('usuarios.store') }}">
            @csrf
            @if(isset($usuario)) @method('PUT') @endif

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Nombre</label>
                    <input type="text" name="nombre" class="form-control"
                           value="{{ $usuario->nombre ?? '' }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control"
                           value="{{ $usuario->email ?? '' }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Rol</label>
                    <select name="rol_id" class="form-select" required>
                        @foreach($roles as $rol)
                        <option value="{{ $rol->id }}"
                            @selected(isset($usuario) && $usuario->rol_id == $rol->id)>
                            {{ $rol->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>

                @if(!isset($usuario))
                <div class="col-md-6 mb-3">
                    <label>Contraseña</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                @endif
            </div>

            <div class="mt-4">
                <button class="btn btn-success">
                    <i class="bi bi-save"></i> Guardar
                </button>
                <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                    Cancelar
                </a>
            </div>

        </form>
    </div>
</div>
@endsection

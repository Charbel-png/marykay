@extends('layouts.admin')

@section('title', 'Nuevo vendedor - Mary Kay · Admin')

@section('content')

<h1 class="h4 mb-3">Registrar nuevo vendedor</h1>

@if($errors->any())
    <div class="alert alert-danger">
        <p class="mb-1"><strong>Hay errores en el formulario:</strong></p>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('vendedores.store') }}"
      method="POST"
      class="card p-3 shadow-sm border-0">
    @csrf

    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre completo *</label>
        <input type="text"
               name="nombre"
               id="nombre"
               class="form-control @error('nombre') is-invalid @enderror"
               value="{{ old('nombre') }}"
               required>
        @error('nombre')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <input type="email"
                   name="email"
                   id="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text"
                   name="telefono"
                   id="telefono"
                   class="form-control @error('telefono') is-invalid @enderror"
                   value="{{ old('telefono') }}">
            @error('telefono')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="estatus" class="form-label">Estatus *</label>
            <select name="estatus"
                    id="estatus"
                    class="form-select @error('estatus') is-invalid @enderror"
                    required>
                <option value="">Selecciona un estatus</option>
                <option value="activo"   {{ old('estatus') === 'activo' ? 'selected' : '' }}>Activo</option>
                <option value="inactivo" {{ old('estatus') === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
            </select>
            @error('estatus')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label for="supervisor_id" class="form-label">Supervisor (opcional)</label>
            <select name="supervisor_id"
                    id="supervisor_id"
                    class="form-select @error('supervisor_id') is-invalid @enderror">
                <option value="">Sin supervisor</option>
                @foreach($supervisores as $sup)
                    <option value="{{ $sup->vendedor_id }}"
                        {{ (string)old('supervisor_id') === (string)$sup->vendedor_id ? 'selected' : '' }}>
                        {{ $sup->nombre }}
                    </option>
                @endforeach
            </select>
            @error('supervisor_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <p class="text-muted small mb-3">
        Los campos marcados con * son obligatorios.
    </p>

    <div class="d-flex justify-content-between">
        <a href="{{ route('vendedores.index') }}"
           class="btn btn-outline-secondary"
           title="Volver al listado de vendedores">
            <i class="bi bi-arrow-left"></i>
        </a>
        <button type="submit"
                class="btn btn-dark"
                title="Guardar vendedor">
            <i class="bi bi-save"></i>
        </button>
    </div>
</form>

@endsection

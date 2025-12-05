@extends('layouts.admin')

@section('title', 'Nuevo cliente - Mary Kay · Admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h3 mb-0">Nuevo cliente</h1>
        <small class="text-muted">Registra un nuevo cliente del sistema</small>
    </div>

    <a href="{{ route('clientes.index') }}"
       class="btn btn-sm btn-outline-dark"
       title="Volver al listado">
        <i class="bi bi-arrow-left"></i>
    </a>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        <strong>Hay errores en el formulario:</strong>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('clientes.store') }}" method="POST" class="card p-3 shadow-sm border-0">
    @csrf

    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <label for="nombres" class="form-label">Nombres *</label>
            <input type="text"
                   name="nombres"
                   id="nombres"
                   class="form-control @error('nombres') is-invalid @enderror"
                   value="{{ old('nombres') }}">
            @error('nombres')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label for="apellidos" class="form-label">Apellidos *</label>
            <input type="text"
                   name="apellidos"
                   id="apellidos"
                   class="form-control @error('apellidos') is-invalid @enderror"
                   value="{{ old('apellidos') }}">
            @error('apellidos')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <label for="email" class="form-label">Correo electrónico *</label>
            <input type="email"
                   name="email"
                   id="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
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

    <div class="mb-3">
        <label for="direccion" class="form-label">Dirección</label>
        <textarea name="direccion"
                  id="direccion"
                  rows="2"
                  class="form-control @error('direccion') is-invalid @enderror">{{ old('direccion') }}</textarea>
        @error('direccion')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-flex justify-content-between align-items-center">
        <span class="text-muted small">Los campos marcados con * son obligatorios.</span>

        <div class="d-flex gap-2">
            <a href="{{ route('clientes.index') }}"
               class="btn btn-outline-dark">
                Cancelar
            </a>
            <button type="submit"
                    class="btn btn-mk"
                    title="Guardar cliente">
                <i class="bi bi-check-square"></i>
            </button>
        </div>
    </div>
</form>

@endsection

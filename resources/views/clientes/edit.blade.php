@extends('layouts.admin')

@section('title', 'Editar cliente - Mary Kay · Admin')

@section('content')

<h1 class="h4 mb-1">Editar cliente</h1>
<p class="text-muted mb-3">
    {{ $cliente->nombre_completo }}
</p>

{{-- Errores de validación --}}
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

<form action="{{ route('clientes.update', $cliente) }}"
      method="POST"
      class="card p-3 shadow-sm border-0">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="nombres" class="form-label">Nombres *</label>
        <input type="text"
               name="nombres"
               id="nombres"
               class="form-control @error('nombres') is-invalid @enderror"
               value="{{ old('nombres', $cliente->nombres) }}"
               required>
        @error('nombres')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="apellidos" class="form-label">Apellidos *</label>
        <input type="text"
               name="apellidos"
               id="apellidos"
               class="form-control @error('apellidos') is-invalid @enderror"
               value="{{ old('apellidos', $cliente->apellidos) }}"
               required>
        @error('apellidos')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Correo electrónico</label>
        <input type="email"
               name="email"
               id="email"
               class="form-control @error('email') is-invalid @enderror"
               value="{{ old('email', $cliente->email) }}">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="telefono" class="form-label">Teléfono</label>
        <input type="text"
               name="telefono"
               id="telefono"
               class="form-control @error('telefono') is-invalid @enderror"
               value="{{ old('telefono', $cliente->telefono) }}">
        @error('telefono')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <p class="text-muted small mb-3">
        Los campos marcados con * son obligatorios.
    </p>

    <div class="d-flex justify-content-between">
        <a href="{{ route('clientes.index') }}"
           class="btn btn-outline-secondary"
           title="Volver al listado de clientes">
            <i class="bi bi-arrow-left"></i>
        </a>

        <button type="submit"
                class="btn btn-dark"
                title="Guardar cambios">
            <i class="bi bi-save"></i>
        </button>
    </div>
</form>

@endsection

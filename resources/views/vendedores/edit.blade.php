@extends('layouts.admin')

@section('title', 'Editar vendedor - Mary Kay · Admin')

@section('content')

@php
    $role = auth()->user()->role ?? null;
@endphp

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h3 mb-0">Editar vendedor</h1>
        <small class="text-muted">
            Actualiza los datos de la consultora / consultor independiente.
        </small>
    </div>

    <a href="{{ route('vendedores.index') }}"
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

<form action="{{ route('vendedores.update', $vendedor) }}"
      method="POST"
      class="card p-3 shadow-sm border-0">
    @csrf
    @method('PUT')

    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <label for="nombre" class="form-label">Nombre *</label>
            <input type="text"
                   name="nombre"
                   id="nombre"
                   class="form-control @error('nombre') is-invalid @enderror"
                   value="{{ old('nombre', $vendedor->nombre) }}">
            @error('nombre')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label for="email" class="form-label">Email</label>
            <input type="email"
                   name="email"
                   id="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email', $vendedor->email) }}">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text"
                   name="telefono"
                   id="telefono"
                   class="form-control @error('telefono') is-invalid @enderror"
                   value="{{ old('telefono', $vendedor->telefono) }}">
            @error('telefono')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center">
        <span class="text-muted small">Los campos marcados con * son obligatorios.</span>

        <div class="d-flex gap-2">
            <a href="{{ route('vendedores.index') }}"
               class="btn btn-outline-dark"
               title="Cancelar">
                <i class="bi bi-arrow-left"></i>
            </a>
            <button type="submit"
                    class="btn btn-mk"
                    title="Guardar cambios">
                <i class="bi bi-check-square"></i>
            </button>
        </div>
    </div>
</form>

@endsection

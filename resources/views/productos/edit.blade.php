@extends('layouts.admin')

@section('title', 'Editar producto - Mary Kay · Admin')

@section('content')

<h1 class="h4 mb-1">Editar producto</h1>
<p class="text-muted mb-3">
    {{ $producto->nombre }}
</p>

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

<form action="{{ route('productos.update', $producto) }}"
      method="POST"
      class="card p-3 shadow-sm border-0">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="sku" class="form-label">SKU *</label>
            <input type="text"
                   name="sku"
                   id="sku"
                   class="form-control @error('sku') is-invalid @enderror"
                   value="{{ old('sku', $producto->sku) }}"
                   required>
            @error('sku')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-8 mb-3">
            <label for="nombre" class="form-label">Nombre *</label>
            <input type="text"
                   name="nombre"
                   id="nombre"
                   class="form-control @error('nombre') is-invalid @enderror"
                   value="{{ old('nombre', $producto->nombre) }}"
                   required>
            @error('nombre')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="mb-3">
        <label for="descripcion" class="form-label">Descripción</label>
        <textarea name="descripcion"
                  id="descripcion"
                  rows="3"
                  class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion', $producto->descripcion) }}</textarea>
        @error('descripcion')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="categoria_id" class="form-label">Categoría *</label>
            <select name="categoria_id"
                    id="categoria_id"
                    class="form-select @error('categoria_id') is-invalid @enderror"
                    required>
                <option value="">Selecciona una categoría</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->categoria_id }}"
                        {{ (string)old('categoria_id', $producto->categoria_id) === (string)$categoria->categoria_id ? 'selected' : '' }}>
                        {{ $categoria->nombre }}
                    </option>
                @endforeach
            </select>
            @error('categoria_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4 mb-3">
            <label for="precio_lista" class="form-label">Precio lista *</label>
            <input type="number"
                   step="0.01"
                   min="0"
                   name="precio_lista"
                   id="precio_lista"
                   class="form-control @error('precio_lista') is-invalid @enderror"
                   value="{{ old('precio_lista', $producto->precio_lista) }}"
                   required>
            @error('precio_lista')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4 mb-3">
            <label for="unidad" class="form-label">Unidad *</label>
            <input type="text"
                   name="unidad"
                   id="unidad"
                   class="form-control @error('unidad') is-invalid @enderror"
                   value="{{ old('unidad', $producto->unidad) }}"
                   required>
            @error('unidad')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <p class="text-muted small mb-3">
        Los campos marcados con * son obligatorios.
    </p>

    <div class="d-flex justify-content-between">
        <a href="{{ route('productos.index') }}"
           class="btn btn-outline-secondary"
           title="Volver al listado de productos">
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

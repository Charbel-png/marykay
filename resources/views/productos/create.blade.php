@extends('layouts.admin')

@section('title', 'Nuevo producto - Mary Kay · Admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h3 mb-0">Nuevo producto</h1>
        <small class="text-muted">
            Registra un producto para el catálogo Mary Kay.
        </small>
    </div>

    <a href="{{ route('productos.index') }}"
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

<form action="{{ route('productos.store') }}"
      method="POST"
      enctype="multipart/form-data"
      class="card p-3 shadow-sm border-0">
    @csrf

    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <label for="sku" class="form-label">SKU *</label>
            <input type="text"
                   name="sku"
                   id="sku"
                   class="form-control @error('sku') is-invalid @enderror"
                   value="{{ old('sku') }}">
            @error('sku')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-8">
            <label for="nombre" class="form-label">Nombre *</label>
            <input type="text"
                   name="nombre"
                   id="nombre"
                   class="form-control @error('nombre') is-invalid @enderror"
                   value="{{ old('nombre') }}">
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
                  class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion') }}</textarea>
        @error('descripcion')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <label for="categoria_id" class="form-label">Categoría *</label>
            <select name="categoria_id"
                    id="categoria_id"
                    class="form-select @error('categoria_id') is-invalid @enderror">
                <option value="">Selecciona una categoría</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->categoria_id }}"
                        {{ old('categoria_id') == $categoria->categoria_id ? 'selected' : '' }}>
                        {{ $categoria->nombre }}
                    </option>
                @endforeach
            </select>
            @error('categoria_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label for="precio_venta" class="form-label">Precio de venta *</label>
                <input type="number"
                    step="0.01"
                    name="precio_venta"
                    id="precio_venta"
                    class="form-control @error('precio_venta') is-invalid @enderror"
                    value="{{ old('precio_venta', $producto->precio_venta ?? null) }}">
                @error('precio_venta')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="existencia" class="form-label">Existencia *</label>
                <input type="number"
                    name="existencia"
                    id="existencia"
                    class="form-control @error('existencia') is-invalid @enderror"
                    value="{{ old('existencia', $producto->existencia ?? 0) }}">
                @error('existencia')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-md-4">
            <label for="existencia" class="form-label">Existencia *</label>
            <input type="number"
                   name="existencia"
                   id="existencia"
                   class="form-control @error('existencia') is-invalid @enderror"
                   value="{{ old('existencia', 0) }}">
            @error('existencia')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="mb-3">
        <label for="imagen" class="form-label">Imagen del producto</label>
        <input type="file"
               name="imagen"
               id="imagen"
               class="form-control @error('imagen') is-invalid @enderror"
               accept="image/*">
        @error('imagen')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="text-muted">Formatos: JPG, PNG, WEBP. Máx. 2 MB.</small>
    </div>

    <div class="d-flex justify-content-between align-items-center">
        <span class="text-muted small">Los campos marcados con * son obligatorios.</span>

        <div class="d-flex gap-2">
            <a href="{{ route('productos.index') }}"
               class="btn btn-outline-dark"
               title="Cancelar">
                <i class="bi bi-arrow-left"></i>
            </a>
            <button type="submit"
                    class="btn btn-mk"
                    title="Guardar producto">
                <i class="bi bi-check-square"></i>
            </button>
        </div>
    </div>
</form>

@endsection

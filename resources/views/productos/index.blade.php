@extends('layouts.admin')

@section('title', 'Productos - Mary Kay · Admin')

@section('content')

@php $role = Auth::user()->role ?? null; @endphp

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h3 mb-0">Productos</h1>
        <small class="text-muted">Catálogo administrativo de productos Mary Kay</small>
    </div>

    <div class="d-flex">
        {{-- Filtros / búsqueda --}}
        <form class="d-flex me-2" method="GET" action="{{ route('productos.index') }}">
            <input type="text"
                   name="q"
                   class="form-control form-control-sm me-2"
                   placeholder="Buscar por nombre o SKU"
                   value="{{ request('q') }}">

            <select name="categoria_id"
                    class="form-select form-select-sm me-2"
                    style="max-width: 200px;">
                <option value="">Todas las categorías</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->categoria_id }}"
                        {{ (string)request('categoria_id') === (string)$categoria->categoria_id ? 'selected' : '' }}>
                        {{ $categoria->nombre }}
                    </option>
                @endforeach
            </select>

            <button class="btn btn-sm btn-outline-dark"
                    type="submit"
                    title="Buscar productos">
                <i class="bi bi-search"></i>
            </button>
        </form>

        <a href="{{ route('productos.create') }}"
           class="btn btn-sm btn-dark"
           title="Registrar nuevo producto">
            <i class="bi bi-plus-lg"></i>
        </a>
    </div>
</div>

{{-- Mensajes flash --}}
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@if($productos->isEmpty())
    <div class="alert alert-info">
        No hay productos registrados o no se encontraron resultados.
    </div>
@else
    <div class="card shadow-sm border-0">
        <div class="card-header bg-dark text-white">
            Lista de productos
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>SKU</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Descripción</th>
                            <th class="text-end">Precio lista</th>
                            <th class="text-center">Unidad</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productos as $producto)
                            <tr>
                                <td>{{ $producto->sku }}</td>
                                <td>{{ $producto->nombre }}</td>
                                <td>{{ optional($producto->categoria)->nombre ?? 'Sin categoría' }}</td>
                                <td>
                                    {{ \Illuminate\Support\Str::limit($producto->descripcion, 60) }}
                                </td>
                                <td class="text-end">
                                    ${{ number_format($producto->precio_lista, 2) }}
                                </td>
                                <td class="text-center">
                                    {{ $producto->unidad }}
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('productos.edit', $producto) }}"
                                    class="btn btn-sm btn-outline-primary"
                                    title="Editar producto">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    @if($role === 'admin')
                                        <form action="{{ route('productos.destroy', $producto) }}"
                                            method="POST"
                                            class="d-inline-block"
                                            onsubmit="return confirm('¿Seguro que deseas eliminar este producto?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Eliminar producto">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif

@endsection

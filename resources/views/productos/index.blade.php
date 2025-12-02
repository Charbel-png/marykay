@extends('layouts.admin')

@section('title', 'Productos - Mary Kay · Admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h3 mb-0">Productos</h1>
        <small class="text-muted">Administración del catálogo Mary Kay</small>
    </div>

    <div class="d-flex">
        <form class="d-flex me-2" method="GET" action="{{ route('productos.index') }}">
            <input type="text"
                   name="q"
                   class="form-control form-control-sm me-2"
                   placeholder="Buscar por nombre o SKU"
                   value="{{ request('q') }}">
            <button class="btn btn-sm btn-outline-dark"
                    type="submit"
                    title="Buscar productos">
                <i class="bi bi-search"></i>
            </button>
        </form>

        <a href="{{ route('productos.create') }}"
           class="btn btn-sm btn-mk"
           title="Registrar nuevo producto">
            <i class="bi bi-plus-lg"></i>
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
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
                            <th>Foto</th>
                            <th>Nombre</th>
                            <th>SKU</th>
                            <th class="text-end">Precio venta</th>
                            <th class="text-center">Existencia</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productos as $producto)
                            @php
                                if ($producto->imagen) {
                                    $img = asset('storage/'.$producto->imagen);
                                } else {
                                    $img = asset('img/product-placeholder.png');
                                }
                            @endphp

                            <tr>
                                <td>
                                    <img src="{{ $img }}"
                                         alt="{{ $producto->nombre }}"
                                         style="width: 48px; height: 48px; object-fit: cover; border-radius: .75rem;">
                                </td>
                                <td>{{ $producto->nombre }}</td>
                                <td>{{ $producto->sku }}</td>
                                <td class="text-end">
                                    ${{ number_format($producto->precio_venta, 2) }}
                                </td>
                                <td class="text-center">
                                    {{ $producto->existencia }}
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('productos.edit', $producto) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       title="Editar producto">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

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

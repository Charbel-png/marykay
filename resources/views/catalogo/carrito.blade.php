@extends('layouts.client')

@section('title', 'Mi pedido - Mary Kay')

@section('content')

<h1 class="h4 mb-3">Mi pedido</h1>

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

@if(empty($carrito))
    <div class="alert alert-info">
        Tu pedido está vacío. Ve al catálogo para añadir productos.
    </div>

    <a href="{{ route('catalogo.index') }}"
       class="btn btn-mk">
        Ir al catálogo
    </a>
@else
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Producto</th>
                            <th>SKU</th>
                            <th class="text-center">Cantidad</th>
                            <th class="text-end">Precio unitario</th>
                            <th class="text-end">Subtotal</th>
                            <th class="text-end">IVA</th>
                            <th class="text-end">Total línea</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($carrito as $item)
                            <tr>
                                <td>{{ $item['nombre'] }}</td>
                                <td>{{ $item['sku'] }}</td>
                                <td class="text-center">{{ $item['cantidad'] }}</td>
                                <td class="text-end">${{ number_format($item['precio'], 2) }}</td>
                                <td class="text-end">${{ number_format($item['subtotal'], 2) }}</td>
                                <td class="text-end">${{ number_format($item['iva'], 2) }}</td>
                                <td class="text-end">${{ number_format($item['total'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row justify-content-end mb-3">
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total del pedido:</span>
                        <strong>${{ number_format($total, 2) }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('catalogo.confirmar') }}" method="POST">
        @csrf
        <button type="submit"
                class="btn btn-mk">
            Confirmar pedido
        </button>
        <a href="{{ route('catalogo.index') }}"
           class="btn btn-outline-dark ms-2">
            Seguir comprando
        </a>
    </form>
@endif

@endsection

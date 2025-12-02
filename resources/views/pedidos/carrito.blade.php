@extends('layouts.client')

@section('title', 'Mi pedido - Mary Kay')

@section('content')

<h1 class="h4 mb-3">Mi pedido</h1>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if(empty($carrito))
    <div class="alert alert-info">
        Tu pedido está vacío. Regresa al
        <a href="{{ route('catalogo.index') }}">catálogo</a> para añadir productos.
    </div>
@else
    <form action="{{ route('carrito.vaciar') }}"
          method="POST"
          class="mb-2 text-end">
        @csrf
        <button type="submit"
                class="btn btn-outline-danger btn-sm"
                onclick="return confirm('¿Seguro que deseas cancelar todo el pedido?');">
            Vaciar pedido
        </button>
    </form>

    <div class="card shadow-sm border-0">
        <div class="card-body table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>SKU</th>
                        <th class="text-end">Precio</th>
                        <th class="text-center">Cantidad</th>
                        <th class="text-end">Subtotal</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalTabla = 0; @endphp
                    @foreach($carrito as $item)
                        @php
                            $subtotal = $item['precio'] * $item['cantidad'];
                            $totalTabla += $subtotal;
                        @endphp
                        <tr>
                            <td>{{ $item['nombre'] }}</td>
                            <td>{{ $item['sku'] }}</td>
                            <td class="text-end">
                                ${{ number_format($item['precio'], 2) }}
                            </td>
                            <td class="text-center">
                                <form action="{{ route('carrito.actualizar', $item['producto_id']) }}"
                                      method="POST"
                                      class="d-inline-flex">
                                    @csrf
                                    <input type="number"
                                           name="cantidad"
                                           min="1"
                                           value="{{ $item['cantidad'] }}"
                                           class="form-control form-control-sm me-2"
                                           style="width: 70px;">
                                    <button type="submit"
                                            class="btn btn-sm btn-outline-primary">
                                        Actualizar
                                    </button>
                                </form>
                            </td>
                            <td class="text-end">
                                ${{ number_format($subtotal, 2) }}
                            </td>
                            <td class="text-end">
                                <form action="{{ route('carrito.eliminar', $item['producto_id']) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('¿Eliminar este producto del pedido?');">
                                        Quitar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">Total:</th>
                        <th class="text-end">
                            ${{ number_format($totalTabla, 2) }}
                        </th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <form action="{{ route('catalogo.confirmar') }}"
          method="POST"
          class="mt-3 text-end">
        @csrf
        <button type="submit" class="btn btn-mk">
            Confirmar pedido
        </button>
    </form>
@endif

@endsection

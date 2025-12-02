@extends('layouts.client')

@section('title', 'Detalle del pedido - Mary Kay')

@section('content')

<h1 class="h4 mb-3">Pedido #{{ $pedido->pedido_id }}</h1>

<p class="mb-1">
    <strong>Fecha:</strong> {{ $pedido->fecha }}
</p>
<p class="mb-3">
    <strong>Estado:</strong> {{ optional($pedido->estado)->nombre }}
</p>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-body table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th class="text-center">Cantidad</th>
                    <th class="text-end">Precio unitario</th>
                    <th class="text-end">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($detalles as $detalle)
                    <tr>
                        <td>{{ optional($detalle->producto)->nombre }}</td>
                        <td class="text-center">{{ $detalle->cantidad }}</td>
                        <td class="text-end">
                            ${{ number_format($detalle->precio_unitario, 2) }}
                        </td>
                        <td class="text-end">
                            ${{ number_format($detalle->subtotal_calculado, 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-end">Total:</th>
                    <th class="text-end">
                        ${{ number_format($totales['total'], 2) }}
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<a href="{{ route('cliente.pedidos.index') }}" class="btn btn-outline-dark">
    Volver a mis pedidos
</a>

@endsection

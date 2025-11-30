@extends('layouts.admin')

@section('title', 'Detalle de pedido - Mary Kay · Admin')

@section('content')

<h1 class="h4 mb-1">Pedido #{{ $pedido->pedido_id }}</h1>

<div class="d-flex align-items-center gap-3 mb-3">
    <span class="text-muted">
        {{ $pedido->fecha }}
    </span>

    @php $nombreEstado = optional($pedido->estado)->nombre; @endphp
    @switch($nombreEstado)
        @case('Creado')
            <span class="badge bg-secondary">{{ $nombreEstado }}</span>
            @break
        @case('Pagado')
            <span class="badge bg-info text-dark">{{ $nombreEstado }}</span>
            @break
        @case('Enviado')
            <span class="badge bg-warning text-dark">{{ $nombreEstado }}</span>
            @break
        @case('Entregado')
            <span class="badge bg-success">{{ $nombreEstado }}</span>
            @break
        @case('Cancelado')
            <span class="badge bg-danger">{{ $nombreEstado }}</span>
            @break
        @default
            <span class="badge bg-light text-dark">{{ $nombreEstado ?? '—' }}</span>
    @endswitch
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-dark text-white">
                Cliente
            </div>
            <div class="card-body">
                @if($pedido->cliente)
                    <p class="mb-1"><strong>{{ $pedido->cliente->nombre_completo }}</strong></p>
                    <p class="mb-1">
                        <i class="bi bi-envelope"></i>
                        {{ $pedido->cliente->email ?? 'Sin correo' }}
                    </p>
                    <p class="mb-1">
                        <i class="bi bi-telephone"></i>
                        {{ $pedido->cliente->telefono ?? 'Sin teléfono' }}
                    </p>
                @else
                    <p class="mb-0 text-muted">Sin información de cliente.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-dark text-white">
                Vendedor / Envío
            </div>
            <div class="card-body">
                <p class="mb-2">
                    <strong>Vendedor:</strong>
                    {{ optional($pedido->vendedor)->nombre ?? 'No asignado' }}
                </p>

                <p class="mb-1"><strong>Dirección de envío:</strong></p>
                @if($pedido->direccionEnvio)
                    <p class="mb-0 text-muted">
                        {{ $pedido->direccionEnvio->calle }} {{ $pedido->direccionEnvio->numero }},
                        {{ $pedido->direccionEnvio->colonia }}<br>
                        {{ $pedido->direccionEnvio->cp }} {{ $pedido->direccionEnvio->ciudad }},
                        {{ $pedido->direccionEnvio->estado }}
                    </p>
                @else
                    <p class="mb-0 text-muted">Sin dirección de envío registrada.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-dark text-white">
        Detalle de productos
    </div>
    <div class="card-body p-0">
        @if($detalles->isEmpty())
            <p class="p-3 mb-0 text-muted">Este pedido no tiene líneas de detalle.</p>
        @else
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Producto</th>
                            <th>SKU</th>
                            <th class="text-center">Cantidad</th>
                            <th class="text-end">Precio unitario</th>
                            <th class="text-end">Subtotal</th>
                            <th class="text-end">Descuento</th>
                            <th class="text-end">IVA</th>
                            <th class="text-end">Total línea</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detalles as $detalle)
                            <tr>
                                <td>{{ optional($detalle->producto)->nombre ?? 'Producto eliminado' }}</td>
                                <td>{{ optional($detalle->producto)->sku ?? '—' }}</td>
                                <td class="text-center">{{ $detalle->cantidad }}</td>
                                <td class="text-end">
                                    ${{ number_format($detalle->precio_unitario, 2) }}
                                </td>
                                <td class="text-end">
                                    ${{ number_format($detalle->subtotal_calculado, 2) }}
                                </td>
                                <td class="text-end">
                                    ${{ number_format($detalle->descuento, 2) }}
                                </td>
                                <td class="text-end">
                                    ${{ number_format($detalle->iva_monto, 2) }}
                                </td>
                                <td class="text-end">
                                    ${{ number_format($detalle->total_linea, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<div class="row justify-content-end mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted text-uppercase mb-3">Resumen</h6>
                <div class="d-flex justify-content-between mb-1">
                    <span>Subtotal:</span>
                    <strong>${{ number_format($totales['subtotal'], 2) }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span>Descuento:</span>
                    <strong>- ${{ number_format($totales['descuento'], 2) }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span>IVA:</span>
                    <strong>${{ number_format($totales['iva'], 2) }}</strong>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <span>Total calculado:</span>
                    <strong>${{ number_format($totales['total'], 2) }}</strong>
                </div>
                <div class="d-flex justify-content-between mt-2">
                    <span>Total registrado:</span>
                    <strong>${{ number_format($pedido->total, 2) }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between">
    <a href="{{ route('pedidos.index') }}"
       class="btn btn-outline-secondary"
       title="Volver al listado de pedidos">
        <i class="bi bi-arrow-left"></i>
    </a>
</div>

@endsection

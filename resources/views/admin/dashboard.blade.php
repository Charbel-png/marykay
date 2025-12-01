@extends('layouts.admin')

@section('title', 'Panel administrativo - Mary Kay · Admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h3 mb-0">Panel administrativo</h1>
        <small class="text-muted">
            Hola, {{ $user->name }} ({{ strtoupper($user->role) }})
        </small>
    </div>
</div>

{{-- Tarjetas de resumen --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <p class="text-muted mb-1">Productos</p>
                <h3 class="mb-0">{{ $stats['productos'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <p class="text-muted mb-1">Clientes</p>
                <h3 class="mb-0">{{ $stats['clientes'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <p class="text-muted mb-1">Vendedores</p>
                <h3 class="mb-0">{{ $stats['vendedores'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <p class="text-muted mb-1">Pedidos</p>
                <h3 class="mb-0">{{ $stats['pedidos'] }}</h3>
            </div>
        </div>
    </div>
</div>

{{-- Gráficas --}}
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-dark text-white">
                Pedidos por estado
            </div>
            <div class="card-body">
                @if(count($chartData['pedidosPorEstado']['labels']) > 0)
                    <canvas id="chartPedidosEstado" height="200"></canvas>
                @else
                    <p class="text-muted mb-0">
                        No hay datos suficientes para mostrar esta gráfica.
                    </p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-dark text-white">
                Productos por categoría
            </div>
            <div class="card-body">
                @if(count($chartData['productosPorCategoria']['labels']) > 0)
                    <canvas id="chartProductosCategoria" height="200"></canvas>
                @else
                    <p class="text-muted mb-0">
                        No hay datos suficientes para mostrar esta gráfica.
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Top clientes --}}
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-dark text-white">
        Top 5 clientes por número de pedidos
    </div>
    <div class="card-body p-0">
        @if($topClientes->isEmpty())
            <p class="p-3 mb-0 text-muted">
                Aún no hay pedidos registrados para mostrar clientes frecuentes.
            </p>
        @else
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Cliente</th>
                            <th class="text-center">Pedidos</th>
                            <th class="text-end">Monto total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topClientes as $c)
                            <tr>
                                <td>{{ $c->nombre }}</td>
                                <td class="text-center">{{ $c->total_pedidos }}</td>
                                <td class="text-end">
                                    ${{ number_format($c->monto_total, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const pedidosEstadoLabels = @json($chartData['pedidosPorEstado']['labels']);
    const pedidosEstadoData   = @json($chartData['pedidosPorEstado']['values']);

    const productosCatLabels  = @json($chartData['productosPorCategoria']['labels']);
    const productosCatData    = @json($chartData['productosPorCategoria']['values']);

    // Gráfica de pedidos por estado (barras)
    if (pedidosEstadoLabels.length > 0 && document.getElementById('chartPedidosEstado')) {
        const ctx1 = document.getElementById('chartPedidosEstado').getContext('2d');
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: pedidosEstadoLabels,
                datasets: [{
                    label: 'Pedidos',
                    data: pedidosEstadoData,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                }
            }
        });
    }

    // Gráfica de productos por categoría (doughnut)
    if (productosCatLabels.length > 0 && document.getElementById('chartProductosCategoria')) {
        const ctx2 = document.getElementById('chartProductosCategoria').getContext('2d');
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: productosCatLabels,
                datasets: [{
                    data: productosCatData,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                }
            }
        });
    }
</script>

@endsection

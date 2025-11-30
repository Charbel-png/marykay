<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de administración - Mary Kay</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-uppercase" href="{{ route('admin.dashboard') }}">
            Mary Kay · Admin
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarAdmin" aria-controls="navbarAdmin"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarAdmin">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                       class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        Inicio
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('productos.index') }}"
                       class="nav-link {{ request()->routeIs('productos.index','catalogo.index') ? 'active' : '' }}">
                        Productos
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('clientes.index') }}"
                       class="nav-link {{ request()->routeIs('clientes.index') ? 'active' : '' }}">
                        Clientes
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('vendedores.index') }}"
                       class="nav-link {{ request()->routeIs('vendedores.index') ? 'active' : '' }}">
                        Vendedores
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('pedidos.index') }}"
                       class="nav-link {{ request()->routeIs('pedidos.index') ? 'active' : '' }}">
                        Pedidos
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container py-4">

    <h1 class="h3 mb-4">Panel de administración</h1>

    {{-- Tarjetas de resumen --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase mb-2">Productos</h6>
                    <h2 class="fw-bold mb-0">{{ $totalProductos }}</h2>
                    <small class="text-muted">Registrados en el catálogo</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase mb-2">Clientes</h6>
                    <h2 class="fw-bold mb-0">{{ $totalClientes }}</h2>
                    <small class="text-muted">Clientes registrados</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase mb-2">Vendedores</h6>
                    <h2 class="fw-bold mb-0">{{ $totalVendedores }}</h2>
                    <small class="text-muted">Consultoras activas</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase mb-2">Pedidos</h6>
                    <h2 class="fw-bold mb-0">{{ $totalPedidos }}</h2>
                    <small class="text-muted">Pedidos registrados</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Resumen de ventas --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase mb-2">Total vendido</h6>
                    <h3 class="fw-bold">
                        ${{ number_format($montoTotalVentas, 2) }}
                    </h3>
                    <small class="text-muted">
                        Suma de todos los pedidos registrados en el sistema.
                    </small>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-dark text-white">
                    Últimos pedidos registrados
                </div>
                <div class="card-body p-0">
                    @if($ultimosPedidos->isEmpty())
                        <p class="p-3 mb-0 text-muted">Aún no hay pedidos registrados.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Folio</th>
                                        <th>Fecha</th>
                                        <th>Cliente</th>
                                        <th>Vendedor</th>
                                        <th>Estado</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ultimosPedidos as $pedido)
                                        <tr>
                                            <td>{{ $pedido->pedido_id }}</td>
                                            <td>{{ $pedido->fecha }}</td>
                                            <td>{{ optional($pedido->cliente)->nombre_completo ?? '—' }}</td>
                                            <td>{{ optional($pedido->vendedor)->nombre ?? '—' }}</td>
                                            <td>{{ optional($pedido->estado)->nombre ?? '—' }}</td>
                                            <td class="text-end">
                                                ${{ number_format($pedido->total, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>

</body>
</html>

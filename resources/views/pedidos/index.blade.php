<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedidos - Mary Kay Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-uppercase" href="/">
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

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-0">Pedidos</h1>
            <small class="text-muted">Listado administrativo de pedidos</small>
        </div>

        <form class="d-flex" method="GET" action="{{ route('pedidos.index') }}">
            <input type="text"
                   name="q"
                   class="form-control form-control-sm me-2"
                   placeholder="Buscar por cliente o vendedor"
                   value="{{ request('q') }}">

            <select name="estado_id" class="form-select form-select-sm me-2" style="max-width: 180px;">
                <option value="">Todos los estados</option>
                @foreach($estados as $estado)
                    <option value="{{ $estado->estado_id }}"
                        {{ (string)request('estado_id') === (string)$estado->estado_id ? 'selected' : '' }}>
                        {{ $estado->nombre }}
                    </option>
                @endforeach
            </select>

            <button class="btn btn-sm btn-dark" type="submit">Filtrar</button>
        </form>
    </div>

    @if($pedidos->isEmpty())
        <div class="alert alert-info">
            No hay pedidos registrados o no se encontraron resultados.
        </div>
    @else
        <div class="card">
            <div class="card-header bg-dark text-white">
                Lista de pedidos
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Folio</th>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Vendedor</th>
                                <th>Estado</th>
                                <th class="text-center">Líneas</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pedidos as $pedido)
                                <tr>
                                    <td>{{ $pedido->pedido_id }}</td>
                                    <td>{{ $pedido->fecha }}</td>
                                    <td>{{ optional($pedido->cliente)->nombre_completo ?? '—' }}</td>
                                    <td>{{ optional($pedido->vendedor)->nombre ?? '—' }}</td>
                                    <td>
                                        @php
                                            $nombreEstado = optional($pedido->estado)->nombre;
                                        @endphp

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
                                    </td>
                                    <td class="text-center">
                                        {{ $pedido->detalles_count }}
                                    </td>
                                    <td class="text-end">
                                        ${{ number_format($pedido->total, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

</div>

</body>
</html>

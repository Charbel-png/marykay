<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Vendedores - Mary Kay Admin</title>
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
            <h1 class="h3 mb-0">Vendedores</h1>
            <small class="text-muted">Listado de consultoras/es independientes</small>
        </div>

        <form class="d-flex" method="GET" action="{{ route('vendedores.index') }}">
            <input type="text"
                   name="q"
                   class="form-control form-control-sm me-2"
                   placeholder="Buscar por nombre o email"
                   value="{{ request('q') }}">
            <button class="btn btn-sm btn-dark" type="submit">Buscar</button>
        </form>
    </div>

    @if($vendedores->isEmpty())
        <div class="alert alert-info">
            No hay vendedores registrados o no se encontraron resultados.
        </div>
    @else
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                Lista de vendedores
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Teléfono</th>
                                <th>Estatus</th>
                                <th>Supervisor</th>
                                <th>Pedidos atendidos</th>
                                <th>Fecha de alta</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($vendedores as $vendedor)
                                <tr>
                                    <td>{{ $vendedor->vendedor_id }}</td>
                                    <td>{{ $vendedor->nombre }}</td>
                                    <td>{{ $vendedor->email ?? '—' }}</td>
                                    <td>{{ $vendedor->telefono ?? '—' }}</td>
                                    <td>
                                        @if($vendedor->estatus === 'activo')
                                            <span class="badge bg-success">Activo</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $vendedor->estatus }}</span>
                                        @endif
                                    </td>
                                    <td>{{ optional($vendedor->supervisor)->nombre ?? '—' }}</td>
                                    <td>{{ $vendedor->pedidos_count }}</td>
                                    <td>{{ $vendedor->fecha_alta }}</td>
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

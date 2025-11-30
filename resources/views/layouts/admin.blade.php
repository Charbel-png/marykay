<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Mary Kay · Admin')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap CSS --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar-brand {
            letter-spacing: .05em;
        }
        .table td, .table th {
            vertical-align: middle;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
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
                    <a href="{{ route('productos.index') }}"
                       class="nav-link {{ request()->routeIs('productos.*','catalogo.*') ? 'active' : '' }}">
                        Productos
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('clientes.index') }}"
                       class="nav-link {{ request()->routeIs('clientes.*') ? 'active' : '' }}">
                        Clientes
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('vendedores.index') }}"
                       class="nav-link {{ request()->routeIs('vendedores.*') ? 'active' : '' }}">
                        Vendedores
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('pedidos.index') }}"
                       class="nav-link {{ request()->routeIs('pedidos.*') ? 'active' : '' }}">
                        Pedidos
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container pb-4">
    @yield('content')
</div>

{{-- Bootstrap JS (para menú responsive, etc.) --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

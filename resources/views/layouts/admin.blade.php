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
        :root {
            --mk-pink-bg: #f9d8e5;     /* fondo tipo imagen */
            --mk-pink-light: #f7c1d6;  /* navbar / acentos claros */
            --mk-pink-strong: #e86a9b; /* botones principales */
            --mk-brown: #3b2628;       /* texto oscuro / headers */
        }

        body {
            background-color: var(--mk-pink-bg);
        }

        .navbar {
            background-color: var(--mk-pink-light) !important;
        }

        .navbar-brand {
            letter-spacing: .08em;
            color: var(--mk-brown) !important;
        }

        .nav-link {
            color: #5b4144 !important;
        }

        .nav-link.active {
            color: var(--mk-brown) !important;
            font-weight: 700;
            border-bottom: 2px solid var(--mk-brown);
        }

        .card {
            border-radius: 0.8rem;
        }

        .card-header.bg-dark {
            background-color: var(--mk-brown) !important;
            border-bottom: none;
        }

        .card-header.bg-dark.text-white {
            color: #fbeff5 !important;
        }

        .btn-dark {
            background-color: var(--mk-pink-strong);
            border-color: var(--mk-pink-strong);
        }

        .btn-dark:hover {
            background-color: #d75287;
            border-color: #d75287;
        }

        .btn-outline-dark {
            color: var(--mk-pink-strong);
            border-color: var(--mk-pink-strong);
        }

        .btn-outline-dark:hover {
            background-color: var(--mk-pink-strong);
            color: #fff;
        }

        .table thead.table-light {
            background-color: #fdf1f5;
        }

        .badge.bg-success,
        .badge.bg-info,
        .badge.bg-warning,
        .badge.bg-danger {
            border-radius: 999px;
            padding: 0.3rem 0.7rem;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold text-uppercase" href="{{ route('admin.dashboard') }}">
            MARY KAY · Admin
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
            @auth
                <form action="{{ route('logout') }}" method="POST" class="ms-3">
                    @csrf
                    <button type="submit"
                            class="btn btn-sm btn-outline-dark"
                            title="Cerrar sesión">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>
            @endauth
        </div>
    </div>
</nav>

<div class="container pb-4">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

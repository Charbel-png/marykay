<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Mary Kay · Catálogo')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        :root {
            --mk-pink-bg: #f9d8e5;
            --mk-pink-light: #f7c1d6;
            --mk-pink-strong: #e86a9b;
            --mk-brown: #3b2628;
        }

        body {
            background-color: var(--mk-pink-bg);
        }

        .navbar {
            background-color: var(--mk-pink-light) !important;
        }

        .navbar-brand {
            letter-spacing: .15em;
            text-transform: uppercase;
            color: var(--mk-brown) !important;
        }

        .btn-mk {
            background-color: var(--mk-pink-strong);
            border-color: var(--mk-pink-strong);
        }

        .btn-mk:hover {
            background-color: #d75287;
            border-color: #d75287;
        }

        .card {
            border-radius: 0.8rem;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('catalogo.index') }}">
            MARY KAY
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarClient">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarClient">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a href="{{ route('catalogo.index') }}"
                       class="nav-link {{ request()->routeIs('catalogo.index') ? 'active' : '' }}">
                        Catálogo
                    </a>
                </li>
                {{-- Aquí después: Mis pedidos, Pagos, etc. --}}
            </ul>

            <form action="{{ route('logout') }}" method="POST" class="ms-3">
                @csrf
                <button type="submit"
                        class="btn btn-sm btn-outline-dark"
                        title="Cerrar sesión">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>
</nav>

<div class="container pb-4">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de productos - Mary Kay</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap 5 desde CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #fdf5f9;
        }
        .mk-header {
            background: linear-gradient(90deg, #f48fb1, #f06292);
            color: white;
        }
        .mk-badge {
            background-color: #f48fb1 !important;
        }
        .mk-price {
            color: #c2185b;
            font-weight: 700;
        }
    </style>
</head>
<body>
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

<header class="mk-header py-3 mb-4 shadow-sm">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <h1 class="h3 mb-2 mb-md-0">Catálogo Mary Kay</h1>
        <span class="small">Gestión de productos y categorías</span>
    </div>
</header>

<main class="container mb-5">

    {{-- Filtros --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="GET" action="{{ route('catalogo.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="q" class="form-label">Buscar</label>
                    <input
                        type="text"
                        name="q"
                        id="q"
                        value="{{ request('q') }}"
                        class="form-control"
                        placeholder="Nombre, SKU o descripción..."
                    >
                </div>

                <div class="col-md-4">
                    <label for="categoria_id" class="form-label">Categoría</label>
                    <select name="categoria_id" id="categoria_id" class="form-select">
                        <option value="">Todas</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->categoria_id }}"
                                {{ request('categoria_id') == $categoria->categoria_id ? 'selected' : '' }}>
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary mt-auto w-100">
                        Aplicar filtros
                    </button>
                    <a href="{{ route('catalogo.index') }}" class="btn btn-outline-secondary mt-auto">
                        Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Resultados --}}
    @if ($productos->isEmpty())
        <div class="alert alert-warning">
            No se encontraron productos con los filtros seleccionados.
        </div>
    @else
        <div class="row g-3 g-md-4">
            @foreach ($productos as $producto)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title mb-1">{{ $producto->nombre }}</h5>
                            <small class="text-muted">SKU: {{ $producto->sku }}</small>

                            <p class="mt-2 mb-1">
                                <span class="badge mk-badge">
                                    {{ optional($producto->categoria)->nombre ?? 'Sin categoría' }}
                                </span>
                            </p>

                            <p class="card-text small text-muted flex-grow-1">
                                {{ \Illuminate\Support\Str::limit($producto->descripcion, 90) }}
                            </p>
                        </div>
                        <div class="card-footer d-flex justify-content-between align-items-center">
                            <span class="mk-price">
                                ${{ number_format($producto->precio_lista, 2) }}
                            </span>
                            <button type="button" class="btn btn-sm btn-outline-primary">
                                Añadir al carrito
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Paginación --}}
        <div class="mt-4">
            {{ $productos->links() }}
        </div>
    @endif
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
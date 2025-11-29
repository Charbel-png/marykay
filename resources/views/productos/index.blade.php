<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de productos - Mary Kay</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap CDN para que se vea decente --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-uppercase" href="/">
            Mary Kay · Admin
        </a>

        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a href="{{ route('productos.index') }}" class="nav-link active">
                        Catálogo de productos
                    </a>
                </li>
                {{-- Aquí luego añadimos más módulos (clientes, pedidos, etc.) --}}
            </ul>
        </div>
    </div>
</nav>

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-0">Catálogo de productos</h1>
            <small class="text-muted">Vista administrativa de productos Mary Kay</small>
        </div>

        {{-- Buscador por nombre o SKU --}}
        <form class="d-flex" method="GET" action="{{ route('productos.index') }}">
            <input type="text"
                   name="q"
                   class="form-control form-control-sm me-2"
                   placeholder="Buscar por nombre o SKU"
                   value="{{ request('q') }}">
            <button class="btn btn-sm btn-dark" type="submit">Buscar</button>
        </form>
    </div>

    @if($productos->isEmpty())
        <div class="alert alert-info">
            No hay productos registrados o no se encontraron resultados con ese criterio de búsqueda.
        </div>
    @else
        {{-- Tabla principal con los campos reales de la BD --}}
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                Lista de productos
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>SKU</th>
                                <th>Nombre</th>
                                <th>Categoría</th>
                                <th>Descripción</th>
                                <th class="text-end">Precio lista</th>
                                <th class="text-center">Unidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($productos as $producto)
                                <tr>
                                    <td>{{ $producto->producto_id }}</td>
                                    <td>{{ $producto->sku }}</td>
                                    <td>{{ $producto->nombre }}</td>
                                    <td>
                                        {{-- Nombre de la categoría si existe relación, si no, fallback --}}
                                        {{ optional($producto->categoria)->nombre ?? 'Sin categoría' }}
                                    </td>
                                    <td>
                                        {{ \Illuminate\Support\Str::limit($producto->descripcion, 60) }}
                                    </td>
                                    <td class="text-end">
                                        ${{ number_format($producto->precio_lista, 2) }}
                                    </td>
                                    <td class="text-center">
                                        {{ $producto->unidad }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- (Opcional) Vista en tarjetas, también con campos reales --}}
        <h2 class="h5 mb-3">Vista en tarjetas</h2>
        <div class="row g-3">
            @foreach ($productos as $producto)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="card h-100 shadow-sm">
                        {{-- Como no tenemos imagen en la tabla, usamos un placeholder --}}
                        <img src="https://via.placeholder.com/600x400?text=Mary+Kay"
                             class="card-img-top"
                             alt="{{ $producto->nombre }}">

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title mb-1">
                                {{ $producto->nombre }}
                            </h5>
                            <p class="card-subtitle text-muted mb-2">
                                SKU: {{ $producto->sku }}
                            </p>

                            <p class="card-text small flex-grow-1">
                                {{ \Illuminate\Support\Str::limit($producto->descripcion, 80) }}
                            </p>

                            <div class="mt-2 d-flex justify-content-between align-items-center">
                                <span class="fw-bold">
                                    ${{ number_format($producto->precio_lista, 2) }}
                                </span>
                                <span class="badge bg-secondary">
                                    {{ optional($producto->categoria)->nombre ?? 'Sin categoría' }}
                                </span>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            <button class="btn btn-sm btn-outline-dark" disabled>
                                Ver detalle (pendiente)
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

</body>
</html>

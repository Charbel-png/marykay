@extends('layouts.client')

@section('title', 'Catálogo - Mary Kay')

@section('content')

<div class="mb-3 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="h4 mb-0">Catálogo de productos</h1>
        <small class="text-muted">
            Selecciona tus productos y confirma tu pedido
        </small>
    </div>

    <form class="d-flex" method="GET" action="{{ route('catalogo.index') }}">
        <input type="text"
               name="q"
               class="form-control form-control-sm me-2"
               placeholder="Buscar por nombre o SKU"
               value="{{ request('q') }}">
        <button class="btn btn-sm btn-outline-dark"
                type="submit"
                title="Buscar">
            <i class="bi bi-search"></i>
        </button>
    </form>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="row g-3">

    {{-- 🟣 SECCIÓN PRODUCTOS: casi toda la página --}}
    <div class="col-12 col-xl-10 order-2 order-xl-1">
        @if($productos->isEmpty())
            <div class="alert alert-info">
                Por ahora no hay productos disponibles.
            </div>
        @else
            <div class="row g-3">
                @foreach($productos as $producto)
                    @php
                        // Imagen de producto o placeholder
                        if ($producto->imagen) {
                            $img = asset('storage/'.$producto->imagen);
                        } else {
                            $img = asset('img/product-placeholder.png');
                        }

                        // Producto agotado si no hay existencia
                        $agotado = $producto->existencia <= 0;
                    @endphp

                    <div class="col-12 col-sm-6 col-md-4 col-xl-3">
                        <div class="card h-100 shadow-sm border-0">
                            <img src="{{ $img }}"
                                 alt="{{ $producto->nombre }}"
                                 class="card-img-top"
                                 style="height: 170px; object-fit: cover;">

                            <div class="card-body d-flex flex-column">
                                <small class="text-muted text-uppercase d-flex justify-content-between">
                                    <span>{{ optional($producto->categoria)->nombre ?? 'Sin categoría' }}</span>
                                    @if($agotado)
                                        <span class="badge bg-secondary">Agotado</span>
                                    @endif
                                </small>

                                <h5 class="card-title mt-1">{{ $producto->nombre }}</h5>
                                <p class="card-text small text-muted">
                                    {{ \Illuminate\Support\Str::limit($producto->descripcion, 70) }}
                                </p>

                                <p class="card-text small text-muted mb-2">
                                    Existencia:
                                    {{ $producto->existencia }}
                                    {{ $producto->existencia === 1 ? 'pieza' : 'piezas' }}
                                </p>

                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <strong>${{ number_format($producto->precio_venta, 2) }}</strong>
                                    </div>

                                    @if(!$agotado)
                                        <form action="{{ route('catalogo.agregar', $producto) }}"
                                              method="POST"
                                              class="d-flex align-items-center">
                                            @csrf
                                            <input type="number"
                                                   name="cantidad"
                                                   min="1"
                                                   max="{{ max(1, $producto->existencia) }}"
                                                   value="1"
                                                   class="form-control form-control-sm me-2"
                                                   style="width: 70px;">
                                            <button type="submit"
                                                    class="btn btn-sm btn-mk"
                                                    title="Añadir al pedido">
                                                Añadir
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-sm btn-outline-secondary w-100" disabled>
                                            Agotado
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- 🟡 SECCIÓN "MI PEDIDO": panel compacto a la derecha --}}
    <div class="col-12 col-xl-2 order-1 order-xl-2">
        <div class="card shadow-sm border-0"
             style="max-height: 300px;">
            <div class="card-header bg-dark text-white py-2 d-flex justify-content-between align-items-center">
                <span>Mi pedido</span>
                @if (!empty($carrito) && Route::has('carrito.ver'))
                    <a href="{{ route('carrito.ver') }}"
                       class="btn btn-link btn-sm text-white p-0">
                        Ver todo
                    </a>
                @endif
            </div>
            <div class="card-body py-2">
                @if(empty($carrito))
                    <p class="text-muted mb-0 small">
                        Aún no has añadido productos. Usa el botón <strong>Añadir</strong>.
                    </p>
                @else
                    <div class="mb-2" style="max-height: 160px; overflow-y: auto;">
                        @foreach($carrito as $item)
                            @php
                                // 👇 Aquí se calcula SIEMPRE el subtotal
                                $subtotal = $item['precio'] * $item['cantidad'];
                            @endphp
                            <div class="d-flex justify-content-between mb-1">
                                <div>
                                    <strong class="d-block small">{{ $item['nombre'] }}</strong>
                                    <small class="text-muted">
                                        x{{ $item['cantidad'] }}
                                        · ${{ number_format($item['precio'], 2) }}
                                    </small>
                                </div>
                                <div class="text-end">
                                    <small class="d-block text-muted small">Total</small>
                                    <strong class="small">
                                        ${{ number_format($subtotal, 2) }}
                                    </strong>
                                </div>
                            </div>
                            <hr class="my-1">
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="small">Total del pedido:</span>
                        <strong>${{ number_format($total, 2) }}</strong>
                    </div>

                    <form action="{{ route('catalogo.confirmar') }}"
                          method="POST">
                        @csrf
                        <button type="submit"
                                class="btn btn-mk w-100 btn-sm">
                            Confirmar pedido
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

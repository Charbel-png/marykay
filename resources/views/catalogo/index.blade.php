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
    {{-- Columna izquierda: catálogo --}}
    <div class="col-12 col-lg-8">
        @if($productos->isEmpty())
            <div class="alert alert-info">
                Por ahora no hay productos disponibles.
            </div>
        @else
            <div class="row g-3">
                @foreach($productos as $producto)
                    @php
                        $img = asset('img/product-placeholder.png');
                    @endphp

                    <div class="col-12 col-sm-6 col-md-4">
                        <div class="card h-100 shadow-sm border-0">
                            <img src="{{ $img }}"
                                 alt="{{ $producto->nombre }}"
                                 class="card-img-top">

                            <div class="card-body d-flex flex-column">
                                <small class="text-muted text-uppercase">
                                    {{ optional($producto->categoria)->nombre ?? 'Sin categoría' }}
                                </small>
                                <h5 class="card-title mt-1">{{ $producto->nombre }}</h5>
                                <p class="card-text small text-muted">
                                    {{ \Illuminate\Support\Str::limit($producto->descripcion, 80) }}
                                </p>

                                <div class="mt-auto d-flex justify-content-between align-items-center">
                                    <strong>${{ number_format($producto->precio_lista, 2) }}</strong>

                                    <form action="{{ route('catalogo.agregar', $producto) }}"
                                          method="POST"
                                          class="d-inline">
                                        @csrf
                                        <input type="hidden" name="cantidad" value="1">
                                        <button type="submit"
                                                class="btn btn-sm btn-mk"
                                                title="Añadir al pedido">
                                            Añadir
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Columna derecha: resumen del pedido --}}
    <div class="col-12 col-lg-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-dark text-white">
                Mi pedido
            </div>
            <div class="card-body">
                @if(empty($carrito))
                    <p class="text-muted mb-0">
                        Aún no has añadido productos. Usa el botón <strong>Añadir</strong> en el catálogo.
                    </p>
                @else
                    <div class="mb-2" style="max-height: 260px; overflow-y: auto;">
                        @foreach($carrito as $item)
                            <div class="d-flex justify-content-between mb-2">
                                <div>
                                    <strong class="d-block">{{ $item['nombre'] }}</strong>
                                    <small class="text-muted">
                                        x{{ $item['cantidad'] }} · ${{ number_format($item['precio'], 2) }}
                                    </small>
                                </div>
                                <div class="text-end">
                                    <small class="d-block text-muted">Total</small>
                                    <strong>${{ number_format($item['total'], 2) }}</strong>
                                </div>
                            </div>
                            <hr class="my-1">
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span>Total del pedido:</span>
                        <strong>${{ number_format($total, 2) }}</strong>
                    </div>

                    <form action="{{ route('catalogo.confirmar') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="btn btn-mk w-100">
                            Confirmar pedido
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

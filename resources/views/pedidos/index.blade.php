@extends('layouts.admin')

@section('title', 'Pedidos - Mary Kay · Admin')

@section('content')

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

        <select name="estado_id"
                class="form-select form-select-sm me-2"
                style="max-width: 200px;">
            <option value="">Todos los estados</option>
            @foreach($estados as $estado)
                <option value="{{ $estado->estado_id }}"
                    {{ (string)request('estado_id') === (string)$estado->estado_id ? 'selected' : '' }}>
                    {{ $estado->nombre }}
                </option>
            @endforeach
        </select>

        <button class="btn btn-sm btn-outline-dark"
                type="submit"
                title="Filtrar pedidos">
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

@if($pedidos->isEmpty())
    <div class="alert alert-info">
        No hay pedidos registrados o no se encontraron resultados.
    </div>
@else
    <div class="card shadow-sm border-0">
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
                            <th class="text-end">Acciones</th>
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
                                    @php $nombreEstado = optional($pedido->estado)->nombre; @endphp
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
                                <td class="text-end">
                                    <a href="{{ route('pedidos.show', $pedido) }}"
                                       class="btn btn-sm btn-outline-secondary"
                                       title="Ver detalle del pedido">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif

@endsection

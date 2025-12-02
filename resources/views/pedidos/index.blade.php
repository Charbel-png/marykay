@extends('layouts.admin')

@section('title', 'Pedidos - Mary Kay · Admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h3 mb-0">Pedidos</h1>
        <small class="text-muted">Administración de pedidos de clientes</small>
    </div>

    {{-- Filtro de búsqueda --}}
    <form class="d-flex" method="GET" action="{{ route('pedidos.index') }}">
        <input type="text"
               name="q"
               class="form-control form-control-sm me-2"
               placeholder="Buscar por cliente o vendedor"
               value="{{ request('q') }}">

        <select name="estado_id"
                class="form-select form-select-sm me-2">
            <option value="">Todos los estados</option>
            @foreach($estados as $estado)
                <option value="{{ $estado->estado_id }}"
                    {{ (string)request('estado_id') === (string)$estado->estado_id ? 'selected' : '' }}>
                    {{ $estado->nombre }}
                </option>
            @endforeach
        </select>

        <button class="btn btn-sm btn-outline-dark" type="submit" title="Aplicar filtros">
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
        No hay pedidos registrados o no se encontraron resultados con los filtros aplicados.
    </div>
@else
    <div class="card shadow-sm border-0">
        <div class="card-header bg-dark text-white">
            Lista de pedidos
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Vendedor</th>
                            <th>Estado</th>
                            <th class="text-center">Renglones</th>
                            <th class="text-end">Total</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pedidos as $pedido)
                            @php
                                $clienteNombre = $pedido->cliente
                                    ? trim(($pedido->cliente->nombres ?? '') . ' ' . ($pedido->cliente->apellidos ?? ''))
                                    : '—';

                                $vendedorNombre = $pedido->vendedor->nombre ?? '—';

                                $estadoNombre = optional($pedido->estado)->nombre;
                                $estadoBadgeClass = 'bg-secondary';

                                if ($estadoNombre) {
                                    $nombreLower = mb_strtolower($estadoNombre, 'UTF-8');
                                    if (in_array($nombreLower, ['pendiente'])) {
                                        $estadoBadgeClass = 'bg-warning text-dark';
                                    } elseif (in_array($nombreLower, ['pagado', 'completado'])) {
                                        $estadoBadgeClass = 'bg-success';
                                    } elseif (in_array($nombreLower, ['cancelado', 'cancelada'])) {
                                        $estadoBadgeClass = 'bg-danger';
                                    }
                                }
                            @endphp

                            <tr>
                                <td>{{ $pedido->pedido_id }}</td>
                                <td>{{ $pedido->fecha }}</td>
                                <td>{{ $clienteNombre }}</td>
                                <td>{{ $vendedorNombre }}</td>
                                <td>
                                    <span class="badge {{ $estadoBadgeClass }}">
                                        {{ $estadoNombre ?? 'Sin estado' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    {{ $pedido->detalles_count }}
                                </td>
                                <td class="text-end">
                                    ${{ number_format($pedido->total, 2) }}
                                </td>
                                <td class="text-end">
                                    {{-- Ver detalle --}}
                                    <a href="{{ route('pedidos.show', $pedido) }}"
                                       class="btn btn-sm btn-outline-dark"
                                       title="Ver detalle del pedido">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    {{-- Cancelar (solo si no está ya cancelado) --}}
                                    @php
                                        $estadoLower = mb_strtolower($estadoNombre ?? '', 'UTF-8');
                                    @endphp

                                    @if(!in_array($estadoLower, ['cancelado', 'cancelada']))
                                        <form action="{{ route('pedidos.cancelar', $pedido) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('¿Cancelar este pedido y regresar el stock?');">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Cancelar pedido">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        </form>
                                    @endif
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

@extends('layouts.client')

@section('title', 'Mis pedidos - Mary Kay')

@section('content')

<h1 class="h4 mb-3">Mis pedidos</h1>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if($pedidos->isEmpty())
    <div class="alert alert-info">
        Aún no has realizado pedidos. Ve al
        <a href="{{ route('catalogo.index') }}">catálogo</a> para comenzar.
    </div>
@else
    <div class="card shadow-sm border-0">
        <div class="card-body table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th class="text-end">Total</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pedidos as $pedido)
                        <tr>
                            <td>{{ $pedido->pedido_id }}</td>
                            <td>{{ $pedido->fecha }}</td>
                            <td>{{ optional($pedido->estado)->nombre }}</td>
                            <td class="text-end">
                                ${{ number_format($pedido->total, 2) }}
                            </td>
                            <td class="text-end">
                                <a href="{{ route('cliente.pedidos.show', $pedido) }}"
                                   class="btn btn-sm btn-outline-dark">
                                    Ver
                                </a>

                                @if(optional($pedido->estado)->nombre === 'Pendiente')
                                    <form action="{{ route('cliente.pedidos.cancelar', $pedido) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('¿Cancelar este pedido?');">
                                        @csrf
                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger">
                                            Cancelar
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
@endif

@endsection

@extends('layouts.admin')

@section('title', 'Clientes - Mary Kay · Admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h3 mb-0">Clientes</h1>
        <small class="text-muted">Administración de clientes Mary Kay</small>
    </div>

    <div class="d-flex">
        <form class="d-flex me-2" method="GET" action="{{ route('clientes.index') }}">
            <input type="text"
                   name="q"
                   class="form-control form-control-sm me-2"
                   placeholder="Buscar por nombre o email"
                   value="{{ request('q') }}">
            <button class="btn btn-sm btn-outline-dark"
                    type="submit"
                    title="Buscar clientes">
                <i class="bi bi-search"></i>
            </button>
        </form>

        <a href="{{ route('clientes.create') }}"
           class="btn btn-sm btn-dark"
           title="Registrar nuevo cliente">
            <i class="bi bi-person-plus-fill"></i>
        </a>
    </div>
</div>

{{-- Mensajes flash --}}
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

@if($clientes->isEmpty())
    <div class="alert alert-info">
        No hay clientes registrados o no se encontraron resultados.
    </div>
@else
    <div class="card shadow-sm border-0">
        <div class="card-header bg-dark text-white">
            Lista de clientes
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nombre completo</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Pedidos</th>
                            <th>Fecha registro</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clientes as $cliente)
                            <tr>
                                <td>{{ $cliente->nombre_completo }}</td>
                                <td>{{ $cliente->email ?? '—' }}</td>
                                <td>{{ $cliente->telefono ?? '—' }}</td>
                                <td>{{ $cliente->pedidos_count }}</td>
                                <td>
                                    {{ $cliente->fecha_reg
                                        ? $cliente->fecha_reg->format('Y-m-d H:i')
                                        : '—' }}
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('clientes.edit', $cliente) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       title="Editar cliente">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form action="{{ route('clientes.destroy', $cliente) }}"
                                          method="POST"
                                          class="d-inline-block"
                                          onsubmit="return confirm('¿Seguro que deseas eliminar este cliente?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Eliminar cliente">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
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

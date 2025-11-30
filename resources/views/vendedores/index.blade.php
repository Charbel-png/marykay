@extends('layouts.admin')

@section('title', 'Vendedores - Mary Kay · Admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h3 mb-0">Vendedores</h1>
        <small class="text-muted">Listado de consultoras/es independientes</small>
    </div>

    <div class="d-flex">
        <form class="d-flex me-2" method="GET" action="{{ route('vendedores.index') }}">
            <input type="text"
                   name="q"
                   class="form-control form-control-sm me-2"
                   placeholder="Buscar por nombre o email"
                   value="{{ request('q') }}">
            <button class="btn btn-sm btn-outline-dark"
                    type="submit"
                    title="Buscar vendedores">
                <i class="bi bi-search"></i>
            </button>
        </form>

        <a href="{{ route('vendedores.create') }}"
           class="btn btn-sm btn-dark"
           title="Registrar nuevo vendedor">
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

@if($vendedores->isEmpty())
    <div class="alert alert-info">
        No hay vendedores registrados o no se encontraron resultados.
    </div>
@else
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-dark text-white">
            Lista de vendedores
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Estatus</th>
                            <th>Supervisor</th>
                            <th>Pedidos atendidos</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vendedores as $vendedor)
                            <tr>
                                <td>{{ $vendedor->nombre }}</td>
                                <td>{{ $vendedor->email ?? '—' }}</td>
                                <td>{{ $vendedor->telefono ?? '—' }}</td>
                                <td>
                                    @if($vendedor->estatus === 'activo')
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-secondary">Inactivo</span>
                                    @endif
                                </td>
                                <td>{{ optional($vendedor->supervisor)->nombre ?? '—' }}</td>
                                <td>{{ $vendedor->pedidos_count }}</td>
                                <td class="text-end">
                                    <a href="{{ route('vendedores.edit', $vendedor) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       title="Editar vendedor">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form action="{{ route('vendedores.destroy', $vendedor) }}"
                                          method="POST"
                                          class="d-inline-block"
                                          onsubmit="return confirm('¿Seguro que deseas eliminar este vendedor?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Eliminar vendedor">
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

{{-- resources/views/clientes/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Clientes - Mary Kay · Admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h3 mb-0">Clientes</h1>
        <small class="text-muted">Administración de clientes Mary Kay</small>
    </div>

    <a href="{{ route('clientes.create') }}"
       class="btn btn-mk btn-sm"
       title="Nuevo cliente">
        <i class="bi bi-person-plus"></i>
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if($clientes->isEmpty())
    <div class="alert alert-info">
        No hay clientes registrados.
    </div>
@else
    <div class="card shadow-sm border-0">
        <div class="card-body table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre completo</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clientes as $cliente)
                        <tr>
                            <td>{{ $cliente->cliente_id }}</td>
                            <td>
                                {{ $cliente->nombres }} {{ $cliente->apellidos }}
                            </td>
                            <td>{{ $cliente->email }}</td>
                            <td>{{ $cliente->telefono }}</td>
                            <td class="text-end">
                                <a href="{{ route('clientes.edit', $cliente) }}"
                                   class="btn btn-sm btn-outline-dark"
                                   title="Editar cliente">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <form action="{{ route('clientes.destroy', $cliente) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('¿Eliminar este cliente?');">
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
@endif

@endsection

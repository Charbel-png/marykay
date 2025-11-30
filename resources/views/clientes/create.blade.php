<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo cliente - Mary Kay Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-uppercase" href="{{ route('admin.dashboard') }}">
            Mary Kay · Admin
        </a>

        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a href="{{ route('clientes.index') }}" class="nav-link">Clientes</a></li>
                <li class="nav-item"><a href="{{ route('productos.index') }}" class="nav-link">Productos</a></li>
                <li class="nav-item"><a href="{{ route('pedidos.index') }}" class="nav-link">Pedidos</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container py-4">

    <h1 class="h4 mb-3">Registrar nuevo cliente</h1>

    {{-- Mensajes de éxito --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Errores de validación --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <p class="mb-1"><strong>Hay errores en el formulario:</strong></p>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('clientes.store') }}" method="POST" class="card p-3 shadow-sm border-0">
        @csrf

        <div class="mb-3">
            <label for="nombres" class="form-label">Nombres *</label>
            <input type="text"
                   name="nombres"
                   id="nombres"
                   class="form-control @error('nombres') is-invalid @enderror"
                   value="{{ old('nombres') }}"
                   required>
            @error('nombres')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="apellidos" class="form-label">Apellidos *</label>
            <input type="text"
                   name="apellidos"
                   id="apellidos"
                   class="form-control @error('apellidos') is-invalid @enderror"
                   value="{{ old('apellidos') }}"
                   required>
            @error('apellidos')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <input type="email"
                   name="email"
                   id="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text"
                   name="telefono"
                   id="telefono"
                   class="form-control @error('telefono') is-invalid @enderror"
                   value="{{ old('telefono') }}">
            @error('telefono')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <p class="text-muted small mb-3">Los campos marcados con * son obligatorios.</p>

        <div class="d-flex justify-content-between">
            <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary">
                Cancelar
            </a>
            <button type="submit" class="btn btn-dark">
                Guardar cliente
            </button>
        </div>
    </form>

</div>

</body>
</html>

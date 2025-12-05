{{-- resources/views/auth/register.blade.php --}}
@extends('layouts.auth') {{-- o el layout que uses en login --}}

@section('title', 'Crear cuenta - Mary Kay')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow border-0" style="max-width: 480px; width: 100%;">
        <div class="card-body p-4 p-md-5">
            <h1 class="h3 text-center mb-3" style="letter-spacing: 0.3em;">
                MARY KAY
            </h1>
            <p class="text-center text-muted mb-4">
                Crea tu cuenta para comprar en el catálogo
            </p>

            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>Hay errores en el formulario:</strong>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}">
                @csrf

                <div class="mb-3">
                    <label for="nombres" class="form-label">Nombres *</label>
                    <input type="text"
                           class="form-control @error('nombres') is-invalid @enderror"
                           id="nombres"
                           name="nombres"
                           value="{{ old('nombres') }}">
                    @error('nombres')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="apellidos" class="form-label">Apellidos *</label>
                    <input type="text"
                           class="form-control @error('apellidos') is-invalid @enderror"
                           id="apellidos"
                           name="apellidos"
                           value="{{ old('apellidos') }}">
                    @error('apellidos')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Correo electrónico *</label>
                    <input type="email"
                           class="form-control @error('email') is-invalid @enderror"
                           id="email"
                           name="email"
                           value="{{ old('email') }}">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text"
                           class="form-control @error('telefono') is-invalid @enderror"
                           id="telefono"
                           name="telefono"
                           value="{{ old('telefono') }}">
                    @error('telefono')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña *</label>
                    <input type="password"
                           class="form-control @error('password') is-invalid @enderror"
                           id="password"
                           name="password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">Confirmar contraseña *</label>
                    <input type="password"
                           class="form-control"
                           id="password_confirmation"
                           name="password_confirmation">
                </div>

                <button type="submit"
                        class="btn btn-mk w-100">
                    Crear cuenta
                </button>
            </form>

            <p class="text-center text-muted mt-3 mb-0">
                ¿Ya tienes cuenta?
                <a href="{{ route('login') }}">Inicia sesión</a>
            </p>
        </div>
    </div>
</div>
@endsection

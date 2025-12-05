{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.client') {{-- el mismo layout que ya usabas en el proyecto --}}

@section('title', 'Iniciar sesión - Mary Kay')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow border-0" style="max-width: 480px; width: 100%;">
        <div class="card-body p-4 p-md-5">
            <h1 class="h3 text-center mb-3" style="letter-spacing: 0.3em;">
                MARY KAY
            </h1>

            @if($errors->any())
                <div class="alert alert-danger small">
                    <strong>No se pudo iniciar sesión:</strong>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" novalidate>
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input type="email"
                           class="form-control @error('email') is-invalid @enderror"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           placeholder="ejemplo@correo.com">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password"
                           class="form-control @error('password') is-invalid @enderror"
                           id="password"
                           name="password"
                           placeholder="Ingresa tu contraseña">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit"
                        class="btn btn-mk w-100">
                    Iniciar sesión
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

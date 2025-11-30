<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión - Mary Kay</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        :root {
            --mk-pink-bg: #f9d8e5;
            --mk-pink-light: #f7c1d6;
            --mk-pink-strong: #e86a9b;
            --mk-brown: #3b2628;
        }

        body {
            background-color: var(--mk-pink-bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            max-width: 420px;
            width: 100%;
            border-radius: 1rem;
        }

        .login-title {
            letter-spacing: .15em;
            text-transform: uppercase;
            color: var(--mk-brown);
        }

        .btn-mk {
            background-color: var(--mk-pink-strong);
            border-color: var(--mk-pink-strong);
        }

        .btn-mk:hover {
            background-color: #d75287;
            border-color: #d75287;
        }
    </style>
</head>
<body>

<div class="card shadow-sm border-0 login-card p-3 p-md-4">
    <div class="card-body">
        <h1 class="h4 text-center mb-3 login-title">Mary Kay</h1>
        <p class="text-center text-muted mb-4">
            Inicia sesión para continuar
        </p>

        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email"
                       name="email"
                       id="email"
                       class="form-control"
                       value="{{ old('email') }}"
                       required
                       autofocus>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password"
                       name="password"
                       id="password"
                       class="form-control"
                       required>
            </div>

            <button type="submit"
                    class="btn btn-mk w-100 mt-2">
                Iniciar sesión
            </button>
        </form>
    </div>
</div>

</body>
</html>

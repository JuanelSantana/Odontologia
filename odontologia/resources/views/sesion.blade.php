<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

    <div class="card">
        <h2>Iniciar Sesión</h2>
        <form action="{{ route('usuario.login') }}" method="POST">
            @csrf

            @if (session('success'))
                <div style="color: green; margin-bottom: 10px;">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div style="color: red; margin-bottom: 10px;">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div>
                <input type="text" name="usuario" placeholder="Usuario" required><br><br>
                @error('usuario')
                    <small style="color: red;">{{ $message }}</small>
                @enderror
            </div>
            <br>

            <div>
                <input type="password" name="clave" placeholder="Clave" required><br><br>
                @error('clave')
                    <small style="color: red;">{{ $message }}</small>
                @enderror
            </div>
            <br>
            <button type="submit">Iniciar</button>
            <p>No tienes una cuenta? <a href="/inicio">Registrate</a></p>
        </form>
    </div>

</body>

</html>
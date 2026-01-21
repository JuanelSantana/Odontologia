<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    <div class="card">
        <h2>Registro de Usuario</h2>
        <form action="{{ route('usuario.registrar') }}" method="POST">
            @csrf

            @if (session('success'))
                <div style="color: green; margin-bottom: 15px;">
                    {{ session('success') }}
                </div>
            @endif

            <div>
                <input type="text" name="usuario" placeholder="Usuario" value="{{ old('usuario') }}"> @error('usuario')
                    <small style="color: red;">{{ $message }}</small>
                @enderror
            </div>
            <br>

            <div>
                <input type="email" name="email" placeholder="Email" value="{{ old('email') }}">
                @error('email')
                    <small style="color: red;">{{ $message }}</small>
                @enderror
            </div>
            <br>

            <div>
                <input type="password" name="clave" placeholder="Contraseña">
                @error('clave')
                    <small style="color: red;">{{ $message }}</small>
                @enderror
            </div>
            <br>
            <button type="submit">Registrar</button>
            <p>Ya tienes una cuenta? <a href="/sesion">Inicia Sesion</a></p>
        </form>

    </div>



</body>

</html>
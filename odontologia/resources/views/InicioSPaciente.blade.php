<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Iniciar Sesión</title>
  <link rel="stylesheet" href="{{ asset('css/CSSPACIENTE.css') }}">
</head>

<body>

  <div class="container">
    <!-- Panel lateral -->
    <div class="panel">
      <h2>Bienvenido</h2>
      <p>Para unirte a nuestra comunidad por favor Inicia Sesión con tus datos</p>
      <a href="registrop" class="btn-outline">Registrarse</a>
    </div>

    <!-- Formulario Login -->
    <div class="form-section">
      <h2>Iniciar Sesión</h2>

      <form action="{{ route('pacientes.dashboard') }}" method="GET">
    <div class="form-group">
      <label for="cedula">Cédula</label>
      <input type="text" id="cedula" placeholder="Ej: 001-0000000-1">
    </div>

        <div class="form-group">
          <label for="password">Contraseña</label>
          <input type="password" id="contrsena" placeholder="Tu contraseña">
        </div>

    <button type="submit" class="btn-submit">Iniciar Sesión</button>
</form>
    </div>
  </div>

</body>

</html>
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
      <a href="{{ route('index') }}" class="btn-outline" style="margin-top: 15px;">Volver al Inicio</a>
    </div>

    <!-- Formulario Login -->
    <div class="form-section">
      <h2>Iniciar Sesión</h2>

      @if(session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div>
      @endif

      @if($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('paciente.login') }}" method="POST">
        @csrf
        <div class="form-group">
          <label for="cedula">Cédula</label>
          <input type="text" name="cedula" id="cedula" placeholder="Ej: 001-0000000-1" required>
        </div>

        <div class="form-group">
          <label for="password">Contraseña</label>
          <input type="password" name="password" id="contrsena" placeholder="Tu contraseña" required>
        </div>

        <button type="submit" class="btn-submit">Iniciar Sesión</button>
      </form>
    </div>
  </div>


  <script>
    document.getElementById('cedula').addEventListener('input', function (e) {
      let value = e.target.value.replace(/\D/g, ''); // Solo números
      if (value.length > 11) value = value.slice(0, 11); // Máximo 11 dígitos

      let formatted = '';
      if (value.length > 0) {
        formatted = value.substring(0, 3);
        if (value.length > 3) {
          formatted += '-' + value.substring(3, 10);
          if (value.length > 10) {
            formatted += '-' + value.substring(10, 11);
          }
        }
      }
      e.target.value = formatted;
    });

    document.getElementById('cedula').addEventListener('keydown', function (e) {
      if (e.key === 'Backspace' || e.key === 'Delete') {
        let value = e.target.value;
        let selectionStart = e.target.selectionStart;
        if (value[selectionStart - 1] === '-') {
          // Lógica para manejar el cursor si es necesario, 
          // aunque el event 'input' reformatea correctamente.
        }
      }
    });
  </script>
</body>

</html>
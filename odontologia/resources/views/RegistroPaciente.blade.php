<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro de Paciente</title>
  <link rel="stylesheet" href="{{ asset('css/CSSPACIENTE.css') }}">
</head>

<body>

  <div class="container registro">
    <!-- Panel lateral -->
    <div class="panel">
      <h2>Ya tienes cuenta?</h2>
      <p>Si ya tienes una cuenta, inicia sesión para acceder a tus citas y tratamientos</p>
      <a href="iniciop" class="btn-outline">Iniciar Sesión</a>
      <a href="{{ route('index') }}" class="btn-outline" style="margin-top: 15px;">Volver al Inicio</a>
    </div>

    <div class="form-section">
      <h2>Crear una Cuenta</h2>

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

      <form action="{{ route('paciente.registrar') }}" method="POST">
        @csrf

        <div class="form-row">
          <div class="form-group">
            <label for="cedula">Cédula</label>
            <input type="text" name="cedula" id="cedula" placeholder="Ej: 001-0000000-1" required>
          </div>

          <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" name="password" id="contrasena" placeholder="Tu contraseña" required>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" placeholder="Tu nombre">
          </div>
          <div class="form-group">
            <label for="apellido">Apellido</label>
            <input type="text" name="apellido" id="apellido" placeholder="Tu apellido">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="genero">Género</label>
            <select name="genero" id="genero">
              <option value="">Seleccionar</option>
              <option value="M">Masculino</option>
              <option value="F">Femenino</option>
            </select>
          </div>
          <div class="form-group">
            <label for="fecha">Fecha de Nacimiento</label>
            <input type="date" name="fecha_nacimiento" id="fecha">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="telefono">Teléfono</label>
            <input type="text" name="telefono" id="telefono" placeholder="Ej: 809-123-4567">
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" placeholder="correo@ejemplo.com">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="tipo">Tipo Paciente</label>
            <select name="tipo" id="tipo">
              <option value="">Seleccionar</option>
              <option value="Privado">Privado</option>
              <option value="Seguro">Seguro</option>
            </select>
          </div>
          <div class="form-group">
            <label for="condicion">Condición de Salud</label>
            <input type="text" name="condicion" id="condicion" placeholder="Ej: Ninguna, Asma...">
          </div>
        </div>

        <div class="form-group">
          <label for="seguro">Seguro</label>
          <select name="id_seguro" id="seguro">
            <option value="">Seleccionar seguro</option>
            <option value="1">Humano</option>
            <option value="2">ARS Universal</option>
            <option value="3">Senasa</option>
            <option value="4">Mapfre</option>
            <option value="5">BMI</option>
            <option value="6">La Colonial</option>
          </select>
        </div>


        <button type="submit" class="btn-submit">Registrarse</button>
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
      // Evitar que borren los guiones si el cursor está justo después de uno
      // No es estrictamente necesario con la lógica de 'input' anterior, 
      // pero ayuda a la experiencia de usuario.
      if (e.key === 'Backspace' || e.key === 'Delete') {
        let value = e.target.value;
        let selectionStart = e.target.selectionStart;
        if (value[selectionStart - 1] === '-') {
          // Si intenta borrar un guión, movemos el cursor un paso atrás o borramos el número anterior
          // La lógica de 'input' manejará la reformateo.
        }
      }
    });
  </script>
</body>

</html>
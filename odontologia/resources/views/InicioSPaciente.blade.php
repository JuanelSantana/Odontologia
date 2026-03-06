<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Iniciar Sesion</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>

  <div class="container">
    <!-- Panel lateral -->
    <div class="panel">
      <h2>Bienvenido</h2>
      <p>Para unirte a nuestra comunidad por favor Inicia Sesion con tus datos</p>
      <a href="registro.html" class="btn-outline">Registrarse</a>
    </div>

    <!-- Formulario Login -->
    <div class="form-section">
      <h2>Iniciar Sesion</h2>

      <form>
        <div class="form-group">
          <label for="cedula">Cedula</label>
          <input type="text" id="cedula" placeholder="Ej: 00100000001">
        </div>

        <div class="form-group">
          <label for="password">Contrasena</label>
          <input type="password" id="password" placeholder="Tu contrasena">
        </div>

        <button type="submit" class="btn-submit">Iniciar Sesion</button>
      </form>
    </div>
  </div>

</body>
</html>

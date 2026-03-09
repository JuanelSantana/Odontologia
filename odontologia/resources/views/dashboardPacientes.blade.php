<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del Paciente - Dr. Cepin</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
</head>
<body>

    <aside class="sidebar">
        <div class="perfil">
            <div class="foto" style="background-image: url('https://ui-avatars.com/api/?name=Paciente+Prueba&background=6B21A8&color=fff')"></div>
            <div class="nombre">
                <p>Paciente de Prueba</p>
                <span>paciente@correo.com</span>
            </div>
        </div>

        <nav class="menu">
            <ul>
                <li class="list-item active">
                    <a href="#">
                        <span class="icon"><ion-icon name="calendar-outline"></ion-icon></span>
                        <span class="title">Mis Citas</span>
                    </a>
                </li>
                <li class="list-item">
                    <a href="#">
                        <span class="icon"><ion-icon name="document-text-outline"></ion-icon></span>
                        <span class="title">Mi Historial</span>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="cerrar">
            <a href="/" style="text-decoration:none; color:white;">Cerrar Sesión</a>
        </div>
    </aside>

    <main class="main">
        <header>
            <div class="search_bar">
                <h2 style="color: #6B21A8; margin-left: 20px;">Bienvenido, Juan Pérez</h2>
            </div>
        </header>

        <div class="content" style="padding: 20px;">
            <section class="stats-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                <div class="stat-card card-blue" style="background: white; padding: 20px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                    <div class="stat-info">
                        <h3 class="stat-number">Próxima Cita</h3>
                        <p class="stat-label">15 de Marzo - 10:00 AM</p>
                    </div>
                </div>
                <div class="stat-card card-purple" style="background: white; padding: 20px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                    <div class="stat-info">
                        <h3 class="stat-number">Tratamiento</h3>
                        <p class="stat-label">Ortodoncia Activa</p>
                    </div>
                </div>
            </section>
        </div>
    </main>
</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del Paciente</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
</head>
<body>

    <aside class="sidebar">
        <div class="perfil">
            <div class="foto" style=""></div>
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
                        <span class="title">Mis Citas Pendientes</span>
                    </a>
                </li>
                <li class="list-item">
                    <a href="#">
                        <span class="icon"><ion-icon name="document-text-outline"></ion-icon></span>
                        <span class="title">Mi Historial de Citas</span>
                    </a>
                </li>
                <li class="list-item">
                    <a href="#">
                        <span class="icon"><ion-icon name="medkit-outline"></ion-icon></span>
                        <span class="title">Mi Historial Clínico</span>
                    </a>
                </li>
                <li class="list-item">
                    <a href="#">
                        <span class="icon"><ion-icon name="wallet-outline"></ion-icon></span>
                        <span class="title">Mis Facturas</span>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="cerrar">
            <a href="/" style="text-decoration:none; color:white;">Cerrar Sesión</a>
        </div>
    </aside>

    <main class="main" style="margin-left: 280px; width: calc(100% - 280px); position: relative; min-height: 100vh; background: #f8f9fa;">
    
    <header style="
        background-color: #6B21A8; 
        height: 60px; 
        display: flex; 
        align-items: center; 
        justify-content: flex-start; 
        padding: 0 20px; 
        position: fixed; 
        top: 0; 
        right: 0; 
        left: 270px; 
        z-index: 1000;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    ">
        <div style="
            color: white; 
            background: rgba(255, 255, 255, 0.15); 
            padding: 5px 15px; 
            border-radius: 30px; 
            font-size: 13px;
            font-weight: 500;
            border: 1px solid rgba(255, 255, 255, 0.3);
            display: flex;
            align-items: center;
            gap: 8px;
        ">
            <ion-icon name="person-outline" style="font-size: 16px;"></ion-icon>
            Bienvenido, Juan Pérez
        </div>
    </header>

    <div class="content" style="padding: 30px; padding-top: 90px !important;"> <div style="margin-bottom: 30px;">
            <h2 style="color: #333; font-size: 1.6rem; font-weight: 700;">Panel de Control</h2>
            <p style="color: #666;">Aquí puedes ver el resumen de tus citas y salud.</p>
        </div>

        <section class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            
            <div class="stat-card" style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); border-left: 6px solid #6B21A8; display: flex; align-items: center; gap: 20px;">
                <div style="background: #f3e8ff; padding: 15px; border-radius: 12px; color: #6B21A8; font-size: 24px;">
                    <ion-icon name="calendar-clear-outline"></ion-icon>
                </div>
                <div>
                    <p style="color: #888; font-size: 13px; margin: 0;">Próxima Cita</p>
                    <h3 style="margin: 5px 0 0 0; font-size: 1.1rem; color: #333;">15 de Marzo, 10:00 AM</h3>
                </div>
            </div>

            <div class="stat-card" style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); border-left: 6px solid #00b894; display: flex; align-items: center; gap: 20px;">
                <div style="background: #e6fcf5; padding: 15px; border-radius: 12px; color: #00b894; font-size: 24px;">
                    <ion-icon name="checkmark-circle-outline"></ion-icon>
                </div>
                <div>
                    <p style="color: #888; font-size: 13px; margin: 0;">Estado de Cuenta</p>
                    <h3 style="margin: 5px 0 0 0; font-size: 1.1rem; color: #333;">Sin facturas pendientes</h3>
                </div>
            </div>

        </section>
    </div>
</main>
</body>
</html>
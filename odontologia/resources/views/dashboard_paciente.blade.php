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
                <li class="list-item" id="btn-agendar" onclick="mostrarSeccion('seccion-agendar', this)">
                    <a href="javascript:void(0)">
                        <span class="icon"><ion-icon name="add-circle-outline"></ion-icon></span>
                        <span class="title">Agendar Cita</span>
                    </a>
                </li>
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
        padding: 0 10px; 
        position: fixed; 
        top: 0; 
        right: 0; 
        left: 280px; 
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

    <div id="seccion-resumen">
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
        <section id="seccion-agendar" style="display: none; background: white; padding: 30px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
    <div style="background: white; padding: 30px; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-top: 4px solid #6B21A8;">
    <form action="#" method="GET">
        <!--<form action="{{ route('citas.guardar') }}" method="POST">-->
        @csrf
        <input type="hidden" name="id_usuario" value="{{ Auth::user()->id }}">

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            
            <div class="form-group">
                <label style="display: block; color: #5b4a8a; font-size: 13px; font-weight: 600; margin-bottom: 5px;">Cédula Paciente</label>
                <input type="text" name="cedula_paciente" placeholder="001-0000000-1" required 
                    style="width: 100%; padding: 12px; border: 1.5px solid #d8d0f0; border-radius: 10px; outline: none;">
            </div>

            <div class="form-group">
                <label style="display: block; color: #5b4a8a; font-size: 13px; font-weight: 600; margin-bottom: 5px;">Elegir Doctor</label>
                <select name="id_doctor" required style="width: 100%; padding: 12px; border: 1.5px solid #d8d0f0; border-radius: 10px; outline: none; background: #faf8ff;">
                    <option value="">Seleccione un especialista</option>
                    <option value="1">Dr. Cepin (Odontología General)</option>
                    <option value="2">Dra. Garcia (Ortodoncia)</option>
                    <option value="3">Dr. Perez (Endodoncia)</option>
                </select>
            </div>

            <div class="form-group">
                <label style="display: block; color: #5b4a8a; font-size: 13px; font-weight: 600; margin-bottom: 5px;">Fecha de Cita</label>
                <input type="date" name="fecha_cita" required 
                    style="width: 100%; padding: 12px; border: 1.5px solid #d8d0f0; border-radius: 10px; outline: none;">
            </div>

            <div class="form-group">
                <label style="display: block; color: #5b4a8a; font-size: 13px; font-weight: 600; margin-bottom: 5px;">Motivo de Cita</label>
                <select name="motivo" required style="width: 100%; padding: 12px; border: 1.5px solid #d8d0f0; border-radius: 10px; outline: none; background: #faf8ff;">
                    <option value="">Seleccione un motivo</option>
                    <option value="Limpieza">Limpieza Dental</option>
                    <option value="Dolor">Dolor Agudo</option>
                    <option value="Revision">Revisión de Rutina</option>
                    <option value="Otro">Otro</option>
                </select>
            </div>

            <div class="form-group" style="grid-column: span 2;">
                <label style="display: block; color: #5b4a8a; font-size: 13px; font-weight: 600; margin-bottom: 5px;">Comentario</label>
                <textarea name="comentario" rows="3" placeholder="Cuéntanos un poco más sobre tu necesidad..." 
                    style="width: 100%; padding: 12px; border: 1.5px solid #d8d0f0; border-radius: 10px; outline: none; font-family: sans-serif;"></textarea>
            </div>
        </div>

        <button type="submit" class="btn-submit" style="margin-top: 20px; background: #6B21A8; color: white; border: none; padding: 15px; width: 100%; border-radius: 50px; font-weight: 600; cursor: pointer; transition: 0.3s;">
            Agendar Cita Ahora
        </button>
    </form>
</div>
</section>
    </div>
</main>
        <script>
    function mostrarSeccion(idSeccion, elemento) {
        // 1. Ocultar todas las secciones del contenido
        document.getElementById('seccion-resumen').style.display = 'none'; // Asegúrate de que tus tarjetas estén envueltas en un div con este ID
        document.getElementById('seccion-agendar').style.display = 'none';
        
        // 2. Mostrar la sección seleccionada
        document.getElementById(idSeccion).style.display = 'block';
        
        // 3. Cambiar la clase 'active' en el sidebar
        document.querySelectorAll('.list-item').forEach(li => li.classList.remove('active'));
        elemento.classList.add('active');
    }
</script>
</body>
</html>
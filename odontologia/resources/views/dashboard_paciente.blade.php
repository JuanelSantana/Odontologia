<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del Paciente</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <style>
        .section-content {
            display: none;
        }

        .section-content.active {
            display: block;
        }

        .booking-form {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            max-width: 800px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1.5px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 10px;
            margin-top: 10px;
        }

        .service-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px;
            background: #f8fafc;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .service-item:hover {
            background: #f1f5f9;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #def7ec;
            color: #03543f;
        }

        .alert-danger {
            background: #fde8e8;
            color: #9b1c1c;
        }
    </style>
</head>

<body>

    <aside class="sidebar">
        <div class="perfil">
            <div class="foto"
                style="background-image: url('https://ui-avatars.com/api/?name={{ urlencode($paciente->nom_pac . ' ' . $paciente->ape_pac) }}&background=8562a4&color=fff'); background-size: cover;">
            </div>
            <div class="nombre">
                <p>{{ $paciente->nom_pac }} {{ $paciente->ape_pac }}</p>
                <span>{{ $paciente->eml_pac }}</span>
            </div>
        </div>

        <nav class="menu">
            <ul>
                <li class="list-item active" id="li-dashboard" onclick="mostrarSeccion('seccion-dashboard', this)">
                    <a href="javascript:void(0)">
                        <span class="icon"><ion-icon name="home-outline"></ion-icon></span>
                        <span class="title">Resumen</span>
                    </a>
                </li>
                <li class="list-item" id="li-agendar" onclick="mostrarSeccion('seccion-agendar', this)">
                    <a href="javascript:void(0)">
                        <span class="icon"><ion-icon name="add-circle-outline"></ion-icon></span>
                        <span class="title">Agendar Cita</span>
                    </a>
                </li>
                <li class="list-item" id="li-citas" onclick="mostrarSeccion('seccion-citas', this)">
                    <a href="javascript:void(0)">
                        <span class="icon"><ion-icon name="calendar-outline"></ion-icon></span>
                        <span class="title">Mis Citas</span>
                    </a>
                </li>
                <li class="list-item" id="li-historial" onclick="mostrarSeccion('seccion-historial', this)">
                    <a href="javascript:void(0)">
                        <span class="icon"><ion-icon name="medkit-outline"></ion-icon></span>
                        <span class="title">Mi Historial Clínico</span>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="cerrar">
            <form action="{{ route('usuario.logout') }}" method="POST">
                @csrf
                <button type="submit"
                    style="background:none; border:none; color:white; cursor:pointer; font-size: 1rem; padding: 10px 20px; width: 100%; text-align: left;">
                    Cerrar Sesión
                </button>
            </form>
        </div>
    </aside>

    <main class="main"
        style="margin-left: 20vw; width: calc(100% - 20vw); position: relative; min-height: 100vh; background: #f8f9fa;">

        <header
            style="background-color: #6B21A8; height: 60px; display: flex; align-items: center; justify-content: flex-end; padding: 0 30px; position: fixed; top: 0; right: 0; left: 20vw; z-index: 1000; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <div
                style="color: white; background: rgba(255, 255, 255, 0.15); padding: 5px 15px; border-radius: 30px; font-size: 13px; font-weight: 500; border: 1px solid rgba(255, 255, 255, 0.3); display: flex; align-items: center; gap: 8px;">
                <ion-icon name="person-outline" style="font-size: 16px;"></ion-icon>
                Paciente: {{ $paciente->nom_pac }}
            </div>
        </header>

        <div class="content" style="padding: 30px; padding-top: 90px !important;">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin: 0;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- SECCION DASHBOARD -->
            <section id="seccion-dashboard" class="section-content active">
                <div style="margin-bottom: 30px;">
                    <h2 style="color: #333; font-size: 1.6rem; font-weight: 700;">Bienvenido, {{ $paciente->nom_pac }}
                    </h2>
                    <p style="color: #666;">Este es el resumen de tu actividad.</p>
                </div>

                <div class="stats-grid"
                    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 40px;">
                    <div class="stat-card"
                        style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); border-left: 6px solid #6B21A8; display: flex; align-items: center; gap: 20px;">
                        <div
                            style="background: #f3e8ff; padding: 15px; border-radius: 12px; color: #6B21A8; font-size: 24px;">
                            <ion-icon name="calendar-clear-outline"></ion-icon>
                        </div>
                        <div>
                            <p style="color: #888; font-size: 13px; margin: 0;">Próxima Cita</p>
                            <h3 style="margin: 5px 0 0 0; font-size: 1.1rem; color: #333;">
                                @if($proximaCita)
                                    {{ \Carbon\Carbon::parse($proximaCita->fec_cit)->format('d \d\e M, h:i A') }}
                                @else
                                    No tienes citas programadas
                                @endif
                            </h3>
                        </div>
                    </div>

                    <div class="stat-card"
                        style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); border-left: 6px solid #00b894; display: flex; align-items: center; gap: 20px; cursor: pointer;"
                        onclick="mostrarSeccion('seccion-agendar', document.getElementById('li-agendar'))">
                        <div
                            style="background: #e6fcf5; padding: 15px; border-radius: 12px; color: #00b894; font-size: 24px;">
                            <ion-icon name="add-circle-outline"></ion-icon>
                        </div>
                        <div>
                            <p style="color: #888; font-size: 13px; margin: 0;">¿Necesitas atención?</p>
                            <h3 style="margin: 5px 0 0 0; font-size: 1.1rem; color: #333;">Agendar nueva cita</h3>
                        </div>
                    </div>
                </div>

                <div
                    style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05);">
                    <h3 style="margin-bottom: 20px; color: #333;">Citas Recientes</h3>
                    <div class="table-container">
                        <table class="modern-table" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Fecha y Hora</th>
                                    <th>Doctor</th>
                                    <th>Servicios</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($historialCitas->take(5) as $cita)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($cita->fec_cit)->format('d/m/Y h:i A') }}</td>
                                        <td>Dr. {{ $cita->doctor->nom_doc }} {{ $cita->doctor->ape_doc }}</td>
                                        <td>
                                            @foreach($cita->servicios as $srv)
                                                <span
                                                    style="background: #edf2f7; padding: 2px 8px; border-radius: 10px; font-size: 11px; margin-right: 4px;">{{ $srv->nom_srv }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            <span
                                                style="padding: 4px 10px; border-radius: 20px; font-size: 12px; background: 
                                                    @if($cita->id_eci == 1) #fef3c7; color: #92400e; @elseif($cita->id_eci == 3) #def7ec; color: #03543f; @else #f3e8ff; color: #6b21a8; @endif">
                                                {{ $cita->estado->nom_eci }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" style="text-align: center; padding: 30px; color: #888;">No hay
                                            registros de citas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- SECCION AGENDAR -->
            <section id="seccion-agendar" class="section-content">
                <div style="margin-bottom: 30px;">
                    <h2 style="color: #333; font-size: 1.6rem; font-weight: 700;">Agendar Nueva Cita</h2>
                    <p style="color: #666;">Selecciona el horario y los servicios que necesitas.</p>
                </div>

                <form action="{{ route('citas.guardar') }}" method="POST" class="booking-form">
                    @csrf
                    <div class="form-group">
                        <label>Selecciona un Doctor</label>
                        <select name="id_doc" class="form-control" required>
                            <option value="">-- Elige un doctor --</option>
                            @foreach($doctores as $doc)
                                <option value="{{ $doc->id_doc }}">Dr. {{ $doc->nom_doc }} {{ $doc->ape_doc }}
                                    ({{ $doc->especialidad->nom_esp }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Fecha y Hora (Horario: 8:00 AM - 5:00 PM)</label>
                        <input type="datetime-local" name="fec_cit" class="form-control" required
                            min="{{ date('Y-m-d\TH:i') }}">
                    </div>

                    <div class="form-group">
                        <label>Servicios Requeridos</label>
                        <div class="services-grid">
                            @foreach($servicios as $srv)
                                <label class="service-item">
                                    <input type="checkbox" name="id_srv[]" value="{{ $srv->id_srv }}">
                                    <span>{{ $srv->nom_srv }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Motivo de la Cita (Opcional)</label>
                        <textarea name="mtv_cit" class="form-control" rows="3"
                            placeholder="Ej: Dolor en una muela..."></textarea>
                    </div>

                    <div style="margin-top: 30px;">
                        <button type="submit" class="btn-primary" style="width: 100%;">Confirmar y Agendar Cita</button>
                    </div>
                </form>
            </section>

            <!-- SECCION CITAS -->
            <section id="seccion-citas" class="section-content">
                <div style="margin-bottom: 30px;">
                    <h2 style="color: #333; font-size: 1.6rem; font-weight: 700;">Mi Historial Completo</h2>
                    <p style="color: #666;">Consulta todas tus citas pasadas y programadas.</p>
                </div>

                <div
                    style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05);">
                    <div class="table-container">
                        <table class="modern-table" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Doctor</th>
                                    <th>Servicios</th>
                                    <th>Motivo</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($historialCitas as $cita)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($cita->fec_cit)->format('d/m/Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($cita->fec_cit)->format('h:i A') }}</td>
                                        <td>Dr. {{ $cita->doctor->nom_doc }} {{ $cita->doctor->ape_doc }}</td>
                                        <td>
                                            @foreach($cita->servicios as $srv)
                                                <span
                                                    style="background: #edf2f7; padding: 2px 8px; border-radius: 10px; font-size: 11px; margin-right: 4px;">{{ $srv->nom_srv }}</span>
                                            @endforeach
                                        </td>
                                        <td>{{ $cita->mtv_cit ?: 'N/A' }}</td>
                                        <td>
                                            <span
                                                style="padding: 4px 10px; border-radius: 20px; font-size: 12px; background: 
                                                    @if($cita->id_eci == 1) #fef3c7; color: #92400e; @elseif($cita->id_eci == 3) #def7ec; color: #03543f; @else #f3e8ff; color: #6b21a8; @endif">
                                                {{ $cita->estado->nom_eci }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" style="text-align: center; padding: 30px; color: #888;">Aún no
                                            tienes citas registradas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- SECCION HISTORIAL CLINICO -->
            <section id="seccion-historial" class="section-content">
                <div style="margin-bottom: 30px;">
                    <h2 style="color: #333; font-size: 1.6rem; font-weight: 700;">Mi Historial Clínico</h2>
                    <p style="color: #666;">Consulta tus diagnósticos, tratamientos previos y condiciones médicas.</p>
                </div>

                @if($historialClinico)
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 20px;">
                        <div style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); border-top: 5px solid #6B21A8;">
                            <h3 style="color: #6B21A8; margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                                <ion-icon name="clipboard-outline"></ion-icon> Diagnóstico General
                            </h3>
                            <p style="color: #444; line-height: 1.6;">{{ $historialClinico->dig_hcl }}</p>
                        </div>

                        <div style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); border-top: 5px solid #00b894;">
                            <h3 style="color: #00b894; margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                                <ion-icon name="medkit-outline"></ion-icon> Tratamientos Previos
                            </h3>
                            <p style="color: #444; line-height: 1.6;">{{ $historialClinico->trt_prev_hcl ?: 'No se registran tratamientos previos.' }}</p>
                        </div>

                        <div style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); border-top: 5px solid #ff7675;">
                            <h3 style="color: #ff7675; margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                                <ion-icon name="alert-circle-outline"></ion-icon> Alergias Conocidas
                            </h3>
                            <p style="color: #444; line-height: 1.6;">{{ $historialClinico->alg_hcl ?: 'No se registran alergias.' }}</p>
                        </div>

                        <div style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); border-top: 5px solid #0984e3;">
                            <h3 style="color: #0984e3; margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                                <ion-icon name="flask-outline"></ion-icon> Medicamentos Actuales
                            </h3>
                            <p style="color: #444; line-height: 1.6;">{{ $historialClinico->mds_hcl ?: 'No se registran medicamentos.' }}</p>
                        </div>
                    </div>
                @else
                    <div style="background: white; padding: 50px; border-radius: 15px; text-align: center; box-shadow: 0 5px 20px rgba(0,0,0,0.05);">
                        <ion-icon name="document-text-outline" style="font-size: 64px; color: #ccc; margin-bottom: 20px;"></ion-icon>
                        <h3 style="color: #333;">Aún no tienes un historial clínico registrado</h3>
                        <p style="color: #666; max-width: 500px; margin: 10px auto;">Tu historial será generado por el personal médico después de tu primera consulta.</p>
                    </div>
                @endif
            </section>

        </div>
    </main>

    <script>
        function mostrarSeccion(id, element) {
            // Ocultar todas
            document.querySelectorAll('.section-content').forEach(sec => sec.classList.remove('active'));
            // Quitar active del menu
            document.querySelectorAll('.list-item').forEach(li => li.classList.remove('active'));

            // Mostrar seleccionada
            document.getElementById(id).classList.add('active');
            // Activar menu
            element.classList.add('active');

            // Actualizar scroll
            window.scrollTo(0, 0);
        }

        // Si hay errores de validación, volver a la sección de agendar
        @if($errors->any() && !session('success'))
            mostrarSeccion('seccion-agendar', document.getElementById('li-agendar'));
        @endif
    </script>
</body>

</html>
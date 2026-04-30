<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dental Clinic</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/css.css') }}">
    <link rel="icon" href="{{ asset('imgs/logo_diente.png') }}">
</head>

<body>

    <!-- ========== HEADER ========== -->
    <header class="header">
        <div class="header-contenido">


            <!-- Logo -->
            <div class="logo">
                <img src="imgs/logo_diente.png" alt="Diente" class="logo-icono">
                <div class="logo-texto">
                    <span class="logo-nombre" id="#inicio">DR. CEPIN</span>
                    <span class="logo-subtitulo">CLINIC</span>
                </div>
            </div>

            <input type="checkbox" id="check">
            <!-- Menu de navegacion -->
            <nav class="navegacion">
                <ul class="nav-lista">
                    <li><a href="#inicio" class="nav-link activo">Inicio</a></li>
                    <li><a href="#nosotros" class="nav-link">Nosotros</a></li>
                    <li><a href="#servicios" class="nav-link">Servicios</a></li>
                    <li><a href="#contacto" class="nav-link">Contacto</a></li>
                </ul>
            </nav>

            <!-- Iconos -->
            <div class="header-iconos">
                <a href="iniciop" class="icono-login">
                    <img src="https://img.icons8.com/ios/28/6B21A8/user-male-circle.png" alt="Iniciar sesion" class="icono">
                </a>

            </div>
            <!-- menu responsive -->

            <label for="check" class="checkbtn">
                <i class="fa-solid fa-bars open"></i>
                <i class="fa-solid fa-xmark close"></i>
            </label>

        </div>
    </header>

    <!-- ========== SECCION HERO ========== -->
    <section class="hero">
        <div class="hero-contenido">

            <!-- Textos del lado izquierdo -->
            <div class="hero-texto">
                <p class="subtitulo">Cuidamos tu sonrisa, protegemos tu salud.</p>
                <h1 class="titulo">Dr. Cepin Clinic</h1>
                <p class="descripcion">
                    Bienvenidos a Dr. Cepin Clinic. Brindamos atención odontológica de primer nivel con un
                    toque humano y profesional en New Jersey. Transforma tu sonrisa con la tecnología más
                    avanzada y la experiencia que mereces. Cuidamos las sonrisas de toda tu familia, brindando salud
                    dental integral para niños, jóvenes y adultos.
                </p>
                <a href="registrop" class="boton-cita">Agenda tu cita!</a>
            </div>

            <!-- Imagen del lado derecho -->
            <div class="hero-imagen">
                <script type="module"
                    src="https://ajax.googleapis.com/ajax/libs/model-viewer/4.0.0/model-viewer.min.js"></script>
                <model-viewer src="{{ asset('3d/diente.glb') }}" alt="Diente" class="diente" id="diente" auto-rotate
                    camera-controls touch-action="pan-y" shadow-intensity="1"></model-viewer>
                <script>
                    const diente = document.querySelector('#diente');

                    diente.addEventListener('load', () => {
                        const material = diente.model.materials[0];
                        material.pbrMetallicRoughness.setBaseColorFactor([0.70, 0.70, 0.70, 0.8]);
                    });
                </script>
                <div class="banner-morado">
                    <span class="texto-promocion">por tu sonrisa</span>
                    <span class="texto-promocion-borde">Cuidado</span>
                </div>
            </div>

        </div>
    </section>


    <!-- ========== SECCION PROMOCION MORADA ========== -->
    <section class="promocion-morada">
        <h2 class="promocion-titulo" id="nosotros">Acerca de nosotros...</h2>
        <p class="promocion-descripcion">
            En Dr. Cepin Clinic, nos dedicamos a ofrecer servicios odontológicos de alta calidad
            en New Jersey. Nuestro compromiso es brindar una experiencia cómoda y segura, utilizando
            tecnología de vanguardia para garantizar que cada paciente logre la sonrisa saludable y
            radiante que siempre ha deseado.
        </p>

        <br>
        <!-- ========== CARRUSEL DE IMAGENES ========== -->
        <section class="carrusel-seccion">

            <!-- Flecha izquierda -->
            <button class="carrusel-flecha flecha-izquierda">
                <img src="https://img.icons8.com/ios-filled/30/ffffff/chevron-left.png" alt="Anterior">
            </button>

            <!-- Contenedor de imagenes -->
            <div class="carrusel-contenedor">
                <div class="carrusel-imagen">
                    <img src="https://images.unsplash.com/photo-1606811841689-23dfddce3e95?w=400&h=300&fit=crop"
                        alt="Clinica dental">
                </div>
                <div class="carrusel-imagen imagen-central">
                    <img src="https://images.unsplash.com/photo-1588776814546-1ffcf47267a5?w=400&h=300&fit=crop"
                        alt="Tratamiento dental">
                </div>
                <div class="carrusel-imagen">
                    <img src="https://images.unsplash.com/photo-1609840114035-3c981b782dfe?w=400&h=300&fit=crop"
                        alt="Sonrisa perfecta">
                </div>
            </div>

            <!-- Flecha derecha -->
            <button class="carrusel-flecha flecha-derecha">
                <img src="https://img.icons8.com/ios-filled/30/ffffff/chevron-right.png" alt="Siguiente">
            </button>

        </section>
    </section>

    <!-- ========== SECCION SERVICIOS ========== -->
    <section class="servicios-seccion">

        <!-- Titulo con fondo amarillo -->
        <div class="servicios-titulo-contenedor">
            <div class="servicios-titulo-fondo">
                <h2 class="servicios-titulo" id="servicios">Servicios</h2>
                <div class="flecha-abajo"></div>
            </div>
        </div>

        <!-- Descripcion -->
        <p class="servicios-descripcion">
            En Dr. Cepin Clinic brindamos servicios integrales que cubren todas las áreas de la salud oral,
            desde odontología preventiva y limpiezas profundas, hasta procedimientos avanzados de estética
            dental, implantes y rehabilitación oral.
        </p>

        <!-- Contenido principal -->
        <div class="servicios-contenido">

            <!-- Lado izquierdo - Lista de servicios -->
            <div class="servicios-izquierda">

                <!-- Caja de descripcion del servicio -->
                <div class="servicio-descripcion-caja">
                    <h3>Servicios</h3>
                    <p>Brindamos servicios de alta calidad para mantener tu boca sana y estética, entre ellos:</p>
                </div>

                <!-- Lista de servicios (acordeon) -->
                <div class="servicios-lista">
                    <div class="servicio-item" onclick="toggleServicio(this)">
                        <span class="servicio-mas">+</span>
                        <span class="servicio-nombre">Limpieza dental</span>
                        <p class="servicio-detalle">Limpieza profunda para mantener tus dientes sanos y brillantes.</p>
                    </div>
                    <div class="servicio-item" onclick="toggleServicio(this)">
                        <span class="servicio-mas">+</span>
                        <span class="servicio-nombre">Blanqueamiento</span>
                        <p class="servicio-detalle">Tratamiento profesional para una sonrisa mas blanca.</p>
                    </div>
                    <div class="servicio-item" onclick="toggleServicio(this)">
                        <span class="servicio-mas">+</span>
                        <span class="servicio-nombre">Ortodoncia</span>
                        <p class="servicio-detalle">Brackets y alineadores para corregir la posicion de tus dientes.</p>
                    </div>
                    <div class="servicio-item" onclick="toggleServicio(this)">
                        <span class="servicio-mas">+</span>
                        <span class="servicio-nombre">Implantes</span>
                        <p class="servicio-detalle">Reemplazo de dientes perdidos con implantes de titanio.</p>
                    </div>
                    <div class="servicio-item" onclick="toggleServicio(this)">
                        <span class="servicio-mas">+</span>
                        <span class="servicio-nombre">Endodoncia</span>
                        <p class="servicio-detalle">Tratamiento de conducto para salvar dientes danados.</p>
                    </div>
                </div>

            </div>

            <!-- Lado derecho - Imagen -->
            <div class="servicios-derecha">
                <img src="imgs/servicios.jpg" alt="Doctor con radiografia">
            </div>

        </div>

    </section>

    <!-- ========== SECCION TIPS DENTALES ========== -->
    <section class="tips-seccion" id="tips">

        <!-- Titulo -->
        <div class="tips-header">
            <h2 class="tips-titulo">Tips para tu Sonrisa</h2>
            <p class="tips-subtitulo">Consejos de nuestros expertos para mantener una salud dental perfecta</p>
        </div>

        <!-- Cards de tips -->
        <div class="tips-contenido">

            <div class="tip-card">
                <div class="tip-icono">
                    <img src="https://img.icons8.com/fluency/60/tooth.png" alt="Cepillado">
                </div>
                <h3>Cepillado correcto</h3>
                <p>Cepilla tus dientes al menos 2 veces al dia durante 2 minutos, usando movimientos circulares suaves.</p>
            </div>

            <div class="tip-card">
                <div class="tip-icono">
                    <img src="https://img.icons8.com/fluency/60/sparkling.png" alt="Hilo dental">
                </div>
                <h3>Usa hilo dental</h3>
                <p>El hilo dental elimina la placa y restos de comida donde el cepillo no llega. Usalo una vez al dia.</p>
            </div>

            <div class="tip-card">
                <div class="tip-icono">
                    <img src="https://img.icons8.com/fluency/60/no-food.png" alt="Azucar">
                </div>
                <h3>Reduce el azucar</h3>
                <p>El azucar alimenta las bacterias que causan caries. Limita dulces y bebidas azucaradas.</p>
            </div>

            <div class="tip-card">
                <div class="tip-icono">
                    <img src="https://img.icons8.com/fluency/60/calendar.png" alt="Visitas">
                </div>
                <h3>Visitas regulares</h3>
                <p>Visita a tu dentista cada 6 meses para limpiezas profesionales y detectar problemas a tiempo.</p>
            </div>

        </div>
    </section>
    <!-- ========== TITULO PROMOCION ========== -->
    <section class="seccion-titulo">
        <h2 class="titulo-promocion" id="contacto">Contáctanos!</h2>

        <!-- ========== BARRA DE CONTACTO ========== -->
        <section class="barra-contacto">


            <!-- Informacion de contacto -->
            <div class="contacto-info">

                <!-- Direccion -->
                <div class="contacto-item direccion">
                    <img src="https://img.icons8.com/ios-filled/30/ffffff/marker.png" alt="Ubicacion">
                    <div class="contacto-texto">
                        <h3>Dirección</h3>
                        <p>1401 A Overing Street</p>
                        <p>Bronx New York, 10461, United States</p>
                    </div>
                </div>

                <!-- Email -->
                <div class="contacto-item email">
                    <img src="https://img.icons8.com/ios/40/6B21A8/new-post.png" alt="Email">
                    <div class="contacto-texto">
                        <h3>Email</h3>
                        <p>drcepincli@gmail.com</p>
                        <p>drcepincli@hotmail.com</p>
                    </div>
                </div>

                <!-- Telefonos -->
                <div class="contacto-item telefonos">
                    <img src="https://img.icons8.com/ios/40/6B21A8/phone.png" alt="Telefono">
                    <div class="contacto-texto">
                        <h3>Teléfonos</h3>
                        <p>718-239-8241</p>
                        <p>718-239-8241</p>
                    </div>
                </div>

            </div>
        </section>
    </section>

    <!-- ========== PIE DE PAGINA MORADO ========== -->
    <footer class="footer-morado">
        <p>Copyright &copy; 2026 Dr. Cepin Clinic. Todos los derechos reservados.</p>
    </footer>

    <!-- ========== BOTON CHAT ========== -->
    <div id="chat-window" class="chat-window">
        <div class="chat-header">
            <span>Asistente Virtual</span>
            <button id="close-chat">✖</button>
        </div>
        <div id="chat-body" class="chat-body">
            <!-- Messages will appear here -->
        </div>
        <div class="chat-footer">
            <input type="text" id="chat-input" placeholder="Escribe tu mensaje...">
            <button id="send-btn">➤</button>
        </div>
    </div>

    <button class="boton-chat" id="toggle-chat">
        <img src="https://img.icons8.com/ios-filled/24/ffffff/chat.png" alt="Chat">
    </button>
    <script>
        // borra el localstorage cuando se hace reload
        // se va a quitar para producción
        window.addEventListener('beforeunload', function (e) {
            localStorage.clear();
        });

        document.addEventListener('DOMContentLoaded', () => {
            const chatWindow = document.getElementById('chat-window');
            const toggleButton = document.getElementById('toggle-chat');
            const closeButton = document.getElementById('close-chat');
            const chatBody = document.getElementById('chat-body');
            const chatInput = document.getElementById('chat-input');
            const sendButton = document.getElementById('send-btn');

            const WEBHOOK_URL = 'https://hook.us2.make.com/wwnuytvyfi4m371nthturigm2e4s7eua';

            // User ID stored in localStorage
            let iduser = localStorage.getItem('chat_iduser');

            async function initNewChat() {
                try {
                    const response = await fetch(`${WEBHOOK_URL}?Acción=newchat`);
                    if (response.ok) {
                        const newId = await response.text();
                        iduser = newId.replace(/['"]+/g, '').trim();
                        localStorage.setItem('chat_iduser', iduser);
                        console.log('New User ID assigned:', iduser);
                    } else {
                        console.error('Failed to initialize chat:', response.status);
                    }
                } catch (error) {
                    console.error('Error initializing chat:', error);
                }
            }

            // Toggle Chat Window - Initialize chat if no userId exists
            toggleButton.addEventListener('click', async () => {
                const isHidden = chatWindow.style.display === 'none' || chatWindow.style.display === '';

                if (isHidden) {
                    // If opening chat and no userId, initialize newchat
                    if (!iduser) {
                        await initNewChat();
                    }
                    chatWindow.style.display = 'flex';
                    if (chatInput) chatInput.focus();
                } else {
                    chatWindow.style.display = 'none';
                }
            });

            closeButton.addEventListener('click', () => {
                chatWindow.style.display = 'none';
            });

            // Send Message Logic
            async function sendMessage() {
                const message = chatInput.value.trim();
                if (!message) return;

                // Display User Message
                addMessage(message, 'user');
                chatInput.value = '';

                // Show Typing Indicator
                const loadingId = addLoadingIndicator();

                try {
                    // Ensure we have a userId
                    if (!iduser) {
                        await initNewChat(); // Try to get one if missing
                        if (!iduser) throw new Error('No user ID available');
                    }

                    // 2. Chat Interaction (Action=prevchat)
                    const params = new URLSearchParams({
                        Acción: 'prevchat',
                        iduser: iduser,
                        Mensaje: message
                    });

                    const response = await fetch(`${WEBHOOK_URL}?${params.toString()}`);

                    // Remove typing indicator
                    removeLoadingIndicator(loadingId);

                    if (response.ok) {
                        const botResponse = await response.text();
                        addMessage(botResponse, 'bot');
                    } else {
                        addMessage('Lo siento, hubo un error al conectar con el servidor.', 'bot');
                    }

                } catch (error) {
                    removeLoadingIndicator(loadingId);
                    console.error('Error sending message:', error);
                    addMessage('Error de conexión. Por favor intenta de nuevo.', 'bot');
                }
            }

            sendButton.addEventListener('click', sendMessage);

            chatInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') sendMessage();
            });

            // Validations & UI Helpers
            function addMessage(text, sender) {
                const messageDiv = document.createElement('div');
                messageDiv.classList.add('message', sender);
                messageDiv.innerText = text;
                chatBody.appendChild(messageDiv);
                scrollToBottom();
            }

            function addLoadingIndicator() {
                const id = 'loading-' + Date.now();
                const loaderDiv = document.createElement('div');
                loaderDiv.classList.add('message', 'bot', 'loading');
                loaderDiv.id = id;
                loaderDiv.innerText = 'Escribiendo...';
                chatBody.appendChild(loaderDiv);
                scrollToBottom();
                return id;
            }

            function removeLoadingIndicator(id) {
                const loader = document.getElementById(id);
                if (loader) loader.remove();
            }

            function scrollToBottom() {
                chatBody.scrollTop = chatBody.scrollHeight;
            }
        });

    </script>

    <script>
        // Variables del calendario
        let mesActual = new Date().getMonth();
        let anioActual = new Date().getFullYear();
        let fechaSeleccionada = null;

        const meses = ['Ene', 'Feb', 'Mar', 'Abr', 'Mayo', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        // Mostrar/ocultar calendario
        function toggleCalendario() {
            const caja = document.getElementById('calendario-caja');
            caja.style.display = caja.style.display === 'block' ? 'none' : 'block';
            if (caja.style.display === 'block') {
                generarDias();
            }
        }

        function cerrarCalendario() {
            document.getElementById('calendario-caja').style.display = 'none';
        }

        // Cambiar mes
        function cambiarMes(direccion) {
            mesActual += direccion;
            if (mesActual > 11) {
                mesActual = 0;
                anioActual++;
            } else if (mesActual < 0) {
                mesActual = 11;
                anioActual--;
            }
            generarDias();
        }

        // Cambiar año
        function cambiarAnio(direccion) {
            anioActual += direccion;
            generarDias();
        }

        // Generar los dias del mes
        function generarDias() {
            const contenedor = document.getElementById('calendario-dias');
            document.getElementById('mes-actual').textContent = meses[mesActual];
            document.getElementById('anio-actual').textContent = anioActual;

            // Primer dia del mes y total de dias
            const primerDia = new Date(anioActual, mesActual, 1).getDay();
            const totalDias = new Date(anioActual, mesActual + 1, 0).getDate();
            const diasMesAnterior = new Date(anioActual, mesActual, 0).getDate();

            let html = '';

            // Dias del mes anterior
            for (let i = primerDia - 1; i >= 0; i--) {
                html += `<span class="otro-mes">${diasMesAnterior - i}</span>`;
            }

            // Dias del mes actual
            for (let dia = 1; dia <= totalDias; dia++) {
                const esSeleccionado = fechaSeleccionada &&
                    fechaSeleccionada.dia === dia &&
                    fechaSeleccionada.mes === mesActual &&
                    fechaSeleccionada.anio === anioActual;

                html += `<span class="${esSeleccionado ? 'seleccionado' : ''}" onclick="seleccionarDia(${dia})">${dia}</span>`;
            }

            // Dias del mes siguiente
            const totalCeldas = primerDia + totalDias;
            const celdasRestantes = 42 - totalCeldas;
            for (let i = 1; i <= celdasRestantes; i++) {
                html += `<span class="otro-mes">${i}</span>`;
            }

            contenedor.innerHTML = html;
        }

        // Seleccionar un dia
        function seleccionarDia(dia) {
            fechaSeleccionada = { dia, mes: mesActual, anio: anioActual };
            generarDias();
        }

        // Confirmar fecha
        function confirmarFecha() {
            if (fechaSeleccionada) {
                const mes = String(fechaSeleccionada.mes + 1).padStart(2, '0');
                const dia = String(fechaSeleccionada.dia).padStart(2, '0');
                document.getElementById('fecha-mostrada').textContent = `${mes}/${dia}/${fechaSeleccionada.anio}`;
            }
            cerrarCalendario();
        }

        // Iniciar calendario
        generarDias();

        // ===== ACORDEON DE SERVICIOS =====
        function toggleServicio(item) {
            // Cerrar otros abiertos
            document.querySelectorAll('.servicio-item.abierto').forEach(function (otro) {
                if (otro !== item) {
                    otro.classList.remove('abierto');
                    otro.querySelector('.servicio-mas').textContent = '+';
                }
            });

            // Abrir/cerrar el clickeado
            item.classList.toggle('abierto');
            const mas = item.querySelector('.servicio-mas');
            mas.textContent = item.classList.contains('abierto') ? '-' : '+';
        }

        // cerrar menu responsive al dar click
        const check = document.getElementById("check");
        const links = document.querySelectorAll(".nav-link");

        links.forEach(link => {
            link.addEventListener("click", () => {
                check.checked = false;
            });
        });
    </script>

</body>

</html>


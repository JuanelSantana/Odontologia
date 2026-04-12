<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dr. Cepin Clinic</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>

<body>

    <aside class="sidebar">
        <div class="perfil">
            <div class="foto"><ion-icon name="person-outline"></ion-icon></div>
            <div class="nombre">
                <p> {{ Auth::user()->name }}</p>
                <span>{{ Auth::user()->email }}</span>
            </div>
        </div>

        <nav class="menu">
            <ul>
                <li
                    class="list-item {{ request()->routeIs('dashboard') || request()->routeIs('doctor.dashboard') ? 'active' : '' }}">
                    <a href="{{ auth()->user()->type === 'doctor' ? route('doctor.dashboard') : route('dashboard') }}">
                        <span class="icon"><ion-icon name="grid-outline"></ion-icon></span>
                        <span class="title">Dashboard</span>
                    </a>
                </li>
                @if(auth()->user()->type === 'doctor')
                    <!-- DOCTOR SIDEBAR -->
                    <li
                        class="list-item {{ request()->routeIs('doctor.citas.*') || request()->routeIs('doctor.citas') ? 'active' : '' }}">
                        <a href="{{ route('doctor.citas.index') }}">
                            <span class="icon"><ion-icon name="calendar-outline"></ion-icon></span>
                            <span class="title">Citas</span>
                        </a>
                    </li>
                    <li
                        class="list-item {{ request()->routeIs('doctor.consultas.*') || request()->routeIs('doctor.consultas') ? 'active' : '' }}">
                        <a href="{{ route('doctor.consultas.index') }}">
                            <span class="icon"><ion-icon name="document-text-outline"></ion-icon></span>
                            <span class="title">Consultas</span>
                        </a>
                    </li>
                    <li
                        class="list-item {{ request()->routeIs('doctor.tratamientos.*') || request()->routeIs('doctor.tratamientos') ? 'active' : '' }}">
                        <a href="{{ route('doctor.tratamientos.index') }}">
                            <span class="icon"><ion-icon name="medical-outline"></ion-icon></span>
                            <span class="title">Tratamientos</span>
                        </a>
                    </li>
                @else
                    <!-- ADMINISTRATOR SIDEBAR -->
                    <li
                        class="list-item {{ request()->routeIs('mantenimientos') || request()->routeIs('mantenimientos.*') ? 'active' : '' }}">
                        <a href="{{ route('mantenimientos') }}">
                            <span class="icon"><ion-icon name="construct-outline"></ion-icon></span>
                            <span class="title">Mantenimientos</span>
                        </a>
                    </li>
                    <li class="list-item {{ request()->routeIs('procesos.*') ? 'active' : '' }}">
                        <a href="{{ route('procesos.historial.index') }}">
                            <span class="icon"><ion-icon name="document-text-outline"></ion-icon></span>
                            <span class="title">Procesos</span>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>

        <div class="cerrar">
            <form action="{{ route('usuario.logout') }}" method="POST">
                @csrf
                <button type="submit">Cerrar Sesión</button>
            </form>
        </div>
    </aside>

    <main class="main">
        <header>
            <div class="search_bar">
                <input type="text" placeholder="Buscar">
                <button type="submit"><ion-icon name="search-outline"></ion-icon></button>
            </div>
        </header>

        <div class="content">
            @yield('contenido')
        </div>
    </main>

    <script>
        // Función para expandir la barra de búsqueda
        const searchBar = document.querySelector('.search_bar');
        const searchInput = document.querySelector('.search_bar input');

        searchInput.addEventListener('focus', function () {
            searchBar.style.width = '500px'; // 70% más que 200px
        });

        searchInput.addEventListener('blur', function () {
            searchBar.style.width = '200px'; // Tamaño original
        });
    </script>
</body>

</html>
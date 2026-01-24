<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Sidebar</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>

<body>

    <aside class="sidebar">
        <div class="perfil">
            <div class="foto"></div>
            <div class="nombre">
                <p> {{ Auth::user()->name }}</p>
                <span>{{ Auth::user()->email }}</span>
            </div>
        </div>

        <nav class="menu">
            <ul>
                <li class="list-item active">
                    <a href="#">
                        <span class="icon"><ion-icon name="grid-outline"></ion-icon></span>
                        <span class="title">Dashboard</span>
                    </a>
                </li>
                <li class="list-item">
                    <a href="#">
                        <span class="icon"><ion-icon name="construct-outline"></ion-icon></span>
                        <span class="title">Mantenimientos</span>
                    </a>
                </li>
                <li class="list-item">
                    <a href="#">
                        <span class="icon"><ion-icon name="document-text-outline"></ion-icon></span>
                        <span class="title">Consultas</span>
                    </a>
                </li>
                <li class="list-item">
                    <a href="#">
                        <span class="icon"><ion-icon name="bar-chart-outline"></ion-icon></span>
                        <span class="title">Reportes</span>
                    </a>
                </li>
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
        const listItems = document.querySelectorAll('.list-item');

        function activeLink() {
            listItems.forEach((item) => item.classList.remove('active'));
            this.classList.add('active');
        }

        listItems.forEach((item) => item.addEventListener('click', activeLink));

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
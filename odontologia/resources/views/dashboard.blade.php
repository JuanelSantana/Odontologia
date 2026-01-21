<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    <div class="card">
        <h2>Dashboard</h2>
        <p>¡Login exitoso! Bienvenido {{ Auth::user()->name }}</p>
    </div>
</body>

</html>
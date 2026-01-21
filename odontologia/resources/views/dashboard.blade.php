@extends('layouts.app') @section('contenido')
    <div class="card">
        <h2>Dashboard</h2>
        <p>¡Login exitoso! Bienvenido {{ Auth::user()->name }}</p>

        <form action="{{ route('usuario.logout') }}" method="POST">
            @csrf
            <button type="submit" style="background-color: #ff4d4d; color: white;">Cerrar Sesión</button>
        </form>
    </div>
@endsection
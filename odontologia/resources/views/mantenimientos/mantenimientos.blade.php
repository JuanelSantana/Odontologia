@extends('layouts.app')

@section('contenido')
    <nav class="nav-maintenance">
        <ul>
            <li class="{{ request()->routeIs('mantenimientos.pacientes.*') ? 'active' : '' }}">
                <a href="{{ route('mantenimientos.pacientes.index') }}">
                    <ion-icon name="people-outline"></ion-icon> Pacientes
                </a>
            </li>
            <li class="{{ request()->routeIs('mantenimientos.doctores.*') ? 'active' : '' }}">
                <a href="{{ route('mantenimientos.doctores.index') }}">
                    <ion-icon name="person-outline"></ion-icon> Doctores
                </a>
            </li>
            <li class="{{ request()->routeIs('mantenimientos.empleados.*') ? 'active' : '' }}">
                <a href="{{ route('mantenimientos.empleados.index') }}">
                    <ion-icon name="briefcase-outline"></ion-icon> Empleados
                </a>
            </li>
            <li class="{{ request()->routeIs('mantenimientos.materiales.*') ? 'active' : '' }}">
                <a href="{{ route('mantenimientos.materiales.index') }}">
                    <ion-icon name="cube-outline"></ion-icon> Materiales
                </a>
            </li>
            <li class="{{ request()->routeIs('mantenimientos.proveedores.*') ? 'active' : '' }}">
                <a href="{{ route('mantenimientos.proveedores.index') }}">
                    <ion-icon name="business-outline"></ion-icon> Proveedores
                </a>
            </li>
            <li class="{{ request()->routeIs('mantenimientos.seguros.*') ? 'active' : '' }}">
                <a href="{{ route('mantenimientos.seguros.index') }}">
                    <ion-icon name="shield-checkmark-outline"></ion-icon> Seguros
                </a>
            </li>
            <li class="{{ request()->routeIs('mantenimientos.tratamientos.*') ? 'active' : '' }}">
                <a href="{{ route('mantenimientos.tratamientos.index') }}">
                    <ion-icon name="medical-outline"></ion-icon> Tratamientos
                </a>
            </li>
            <li class="{{ request()->routeIs('mantenimientos.usuarios.*') ? 'active' : '' }}">
                <a href="{{ route('mantenimientos.usuarios.index') }}">
                    <ion-icon name="person-circle-outline"></ion-icon> Usuarios
                </a>
            </li>
        </ul>
    </nav>
    <div class="content">
        @if(session('success'))
            <div class="alert alert-success" style="padding: 15px; background: #d4edda; color: #155724; margin-bottom: 20px; border-radius: 4px; border: 1px solid #c3e6cb;">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger" style="padding: 15px; background: #f8d7da; color: #721c24; margin-bottom: 20px; border-radius: 4px; border: 1px solid #f5c6cb;">
                <ul style="margin: 0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('contenidomant')
    </div>
@endsection
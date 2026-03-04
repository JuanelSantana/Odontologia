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
        </ul>
    </nav>
    <div class="content">
        @yield('contenidomant')
    </div>
@endsection
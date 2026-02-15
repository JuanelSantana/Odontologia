@extends('layouts.app')

@section('contenido')
    <nav class="nav-maintenance">
        <ul>
            <li class="{{ request()->routeIs('mantenimientos.especialidades.*') ? 'active' : '' }}">
                <a href="{{ route('mantenimientos.especialidades.index') }}">
                    <ion-icon name="medkit-outline"></ion-icon> Especialidades
                </a>
            </li>
            <li class="{{ request()->routeIs('mantenimientos.pacientes.*') ? 'active' : '' }}">
                <a href="{{ route('mantenimientos.pacientes.index') }}">
                    <ion-icon name="people-outline"></ion-icon> Pacientes
                </a>
            </li>
        </ul>
    </nav>
    <div class="content">
        @yield('contenidomant')
    </div>
@endsection
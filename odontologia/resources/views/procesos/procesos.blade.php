@extends('layouts.app')

@section('contenido')
    <nav class="nav-maintenance">
        <ul>
            <li class="{{ request()->routeIs('procesos.historial.*') ? 'active' : '' }}">
                <a href="{{ route('procesos.historial.index') }}">
                    <ion-icon name="folder-open-outline"></ion-icon> Historial Clínico
                </a>
            </li>
            <li class="{{ request()->routeIs('procesos.pagos.*') ? 'active' : '' }}">
                <a href="{{ route('procesos.pagos.index') }}">
                    <ion-icon name="cash-outline"></ion-icon> Registro de Pagos
                </a>
            </li>
            <li class="{{ request()->routeIs('procesos.citas.*') ? 'active' : '' }}">
                <a href="{{ route('procesos.citas.index') }}">
                    <ion-icon name="calendar-outline"></ion-icon> Citas (Admin)
                </a>
            </li>
            <li class="{{ request()->routeIs('procesos.facturas.*') ? 'active' : '' }}">
                <a href="{{ route('procesos.facturas.index') }}">
                    <ion-icon name="receipt-outline"></ion-icon> Facturación
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

        @yield('contenidoproceso')
    </div>
@endsection

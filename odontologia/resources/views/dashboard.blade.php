@extends('layouts.app') 

@section('contenido')
<style>
    :root {
        --ms-blue: #0078d4;
        --ms-neutral-light: #f3f2f1;
        --ms-neutral-dark: #323130;
        --ms-border: #edebe9;
        --ms-white: #ffffff;
    }

    .dashboard-container {
        padding: 20px;
        background: #faf9f8;
        min-height: calc(100vh - 60px);
    }

    /* KPIs */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: var(--ms-white);
        padding: 20px;
        border-radius: 4px;
        box-shadow: 0 1.6px 3.6px 0 rgba(0,0,0,0.132), 0 0.3px 0.9px 0 rgba(0,0,0,0.108);
        display: flex;
        align-items: center;
        gap: 15px;
        border: 1px solid var(--ms-border);
        transition: transform 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-2px);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .card-blue .stat-icon { background: #deecf9; color: #0078d4; }
    .card-green .stat-icon { background: #dff6dd; color: #107c10; }
    .card-purple .stat-icon { background: #efebf6; color: #5c2d91; }
    .card-orange .stat-icon { background: #fff4ce; color: #d83b01; }

    .stat-info h3 {
        font-size: 24px;
        font-weight: 700;
        margin: 0;
        color: var(--ms-neutral-dark);
    }

    .stat-label {
        margin: 0;
        font-size: 13px;
        color: #605e5d;
    }

    /* Microsoft Style Calendar */
    .calendar-container {
        background: var(--ms-white);
        border-radius: 4px;
        border: 1px solid var(--ms-border);
        box-shadow: 0 1.6px 3.6px 0 rgba(0,0,0,0.132);
        overflow: hidden;
    }

    .calendar-header {
        padding: 15px 20px;
        border-bottom: 1px solid var(--ms-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f8f8f8;
    }

    .calendar-header h2 {
        font-size: 16px;
        font-weight: 600;
        margin: 0;
        color: var(--ms-neutral-dark);
    }

    .calendar-grid {
        display: flex;
        overflow-x: auto;
        padding: 10px 0;
    }

    .calendar-day-column {
        flex: 0 0 180px;
        min-height: 500px;
        border-right: 1px solid var(--ms-border);
        display: flex;
        flex-direction: column;
    }

    .day-header {
        padding: 10px;
        text-align: center;
        border-bottom: 1px solid var(--ms-border);
        background: #fff;
        position: sticky;
        top: 0;
    }

    .day-header.today {
        background: #f3f9ff;
    }

    .day-header.today .day-number {
        background: var(--ms-blue);
        color: white;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .day-name {
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        color: #605e5d;
        display: block;
    }

    .day-number {
        font-size: 20px;
        font-weight: 400;
        margin-top: 5px;
        display: block;
    }

    .day-events {
        padding: 5px;
        flex-grow: 1;
        background: #fff;
    }

    .event-card {
        background: #deecf9;
        border-left: 3px solid var(--ms-blue);
        padding: 8px;
        border-radius: 2px;
        margin-bottom: 8px;
        font-size: 12px;
        cursor: pointer;
        transition: background 0.2s;
    }

    .event-card:hover {
        background: #c7e0f4;
    }

    .event-time {
        font-weight: 600;
        color: var(--ms-blue);
        display: block;
    }

    .event-patient {
        font-weight: 600;
        color: var(--ms-neutral-dark);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
    }

    .event-doctor {
        font-size: 11px;
        color: #605e5d;
    }

    .no-events {
        font-size: 11px;
        color: #a19f9d;
        text-align: center;
        margin-top: 20px;
    }

    /* Scrollbar Style */
    .calendar-grid::-webkit-scrollbar {
        height: 8px;
    }
    .calendar-grid::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    .calendar-grid::-webkit-scrollbar-thumb {
        background: #c8c8c8;
        border-radius: 4px;
    }
    .calendar-grid::-webkit-scrollbar-thumb:hover {
        background: #a6a6a6;
    }
</style>

<div class="dashboard-container">
    <div style="margin-bottom: 25px;">
        <h1 style="font-size: 24px; font-weight: 600; color: var(--ms-neutral-dark); margin: 0;">Panel de Control Dental</h1>
        <p style="color: #605e5d; margin: 5px 0 0 0;">Resumen del sistema y agenda próxima.</p>
    </div>

    <!-- KPIs -->
    <section class="stats-grid">
        <div class="stat-card card-blue">
            <div class="stat-icon">
                <ion-icon name="calendar-outline"></ion-icon>
            </div>
            <div class="stat-info">
                <h3 class="stat-number">{{ $citasHoy }}</h3>
                <p class="stat-label">Citas para Hoy</p>
            </div>
        </div>

        @if(auth()->user()->type !== 'doctor')
        <div class="stat-card card-green">
            <div class="stat-icon">
                <ion-icon name="people-outline"></ion-icon>
            </div>
            <div class="stat-info">
                <h3 class="stat-number">{{ $totalPacientes }}</h3>
                <p class="stat-label">Total Pacientes</p>
            </div>
        </div>
        @endif

        <div class="stat-card card-purple">
            <div class="stat-icon">
                <ion-icon name="time-outline"></ion-icon>
            </div>
            <div class="stat-info">
                <h3 class="stat-number">{{ $citasPendientes }}</h3>
                <p class="stat-label">Citas Pendientes</p>
            </div>
        </div>

        @if(auth()->user()->type !== 'doctor')
        <div class="stat-card card-orange">
            <div class="stat-icon">
                <ion-icon name="star-outline"></ion-icon>
            </div>
            <div class="stat-info">
                <h3 class="stat-number">
                    @if($proximaCita)
                        {{ \Carbon\Carbon::parse($proximaCita->fec_cit)->format('h:i A') }}
                    @else
                        --:--
                    @endif
                </h3>
                <p class="stat-label">Próxima Consulta</p>
            </div>
        </div>
        @endif
    </section>

    <!-- Calendar 20 Days -->
    <div class="calendar-container">
        <div class="calendar-header">
            <h2>Agenda de los Próximos 20 Días</h2>
            <div style="font-size: 13px; color: #605e5d; display: flex; align-items: center; gap: 10px;">
                <span style="display: flex; align-items: center; gap: 4px;">
                    <span style="width: 10px; height: 10px; background: #deecf9; border-left: 2px solid var(--ms-blue); display: inline-block;"></span> 
                    Ocupado
                </span>
            </div>
        </div>
        
        <div class="calendar-grid">
            @foreach($rangoDias as $dia)
                <div class="calendar-day-column">
                    <div class="day-header {{ $dia['es_hoy'] ? 'today' : '' }}">
                        <span class="day-name">{{ $dia['nombre_dia'] }}</span>
                        <span class="day-number">{{ $dia['numero_dia'] }}</span>
                        <span style="font-size: 11px; color: #a19f9d;">{{ $dia['mes'] }}</span>
                    </div>
                    
                    <div class="day-events">
                        @php
                            $citasDeHoy = $citasPorDia->get($dia['fecha'], collect());
                        @endphp
                        
                        @forelse($citasDeHoy as $cita)
                            <div class="event-card" title="{{ $cita->mtv_cit }}">
                                <span class="event-time">{{ \Carbon\Carbon::parse($cita->fec_cit)->format('h:i A') }}</span>
                                <span class="event-patient">{{ $cita->paciente->nom_pac }} {{ $cita->paciente->ape_pac }}</span>
                                <span class="event-doctor">Dr. {{ $cita->doctor->nom_doc }}</span>
                            </div>
                        @empty
                            <div class="no-events">Sin citas</div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
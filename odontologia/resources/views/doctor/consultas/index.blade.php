@extends('layouts.app')

@section('contenido')
<div class="maintenance-view">
    <section class="form-container" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2>Mis Consultas</h2>
            <p>Registro histórico de sus consultas médicas.</p>
        </div>
        <a href="{{ route('doctor.consultas.create') }}" class="btn-primary" style="display: flex; align-items: center; gap: 5px;">
            <ion-icon name="add-circle-outline"></ion-icon> Nueva Consulta
        </a>
    </section>

    <hr>

    <section class="table-container">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Paciente</th>
                    <th>Motivo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($consultas as $consulta)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($consulta->fec_con)->format('d/m/Y h:i A') }}</td>
                        <td>{{ $consulta->paciente->nom_pac }} {{ $consulta->paciente->ape_pac }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($consulta->motivo, 40) }}</td>
                        <td>
                            <a href="{{ route('doctor.consultas.show', $consulta->id_con) }}" class="btn-icon select" title="Ver Detalles">
                                <ion-icon name="eye-outline"></ion-icon>
                            </a>
                            <a href="{{ route('doctor.consultas.edit', $consulta->id_con) }}" class="btn-icon select" title="Editar">
                                <ion-icon name="create-outline"></ion-icon>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: #666;">No hay consultas registradas aún.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>
</div>
@endsection

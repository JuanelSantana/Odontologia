@extends('layouts.app')

@section('contenido')
<div class="maintenance-view">
    <section class="form-container">
        <h2>Mis Citas Programadas</h2>
        <p>Aquí puede visualizar las próximas citas asignadas a usted.</p>
    </section>

    <hr>

    <section class="table-container">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Paciente</th>
                    <th>Estado</th>
                    <th>Motivo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($citas as $cita)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($cita->fec_cit)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($cita->fec_cit)->format('h:i A') }}</td>
                        <td>{{ $cita->paciente->nom_pac }} {{ $cita->paciente->ape_pac }}</td>
                        <td>
                            <span class="badge" style="background-color: {{ $cita->estado->id_eci == 1 ? '#ffc107' : ($cita->estado->id_eci == 2 ? '#28a745' : '#dc3545') }}; padding: 4px 8px; border-radius: 12px; font-size: 12px; color: white;">
                                {{ $cita->estado->nom_eci }}
                            </span>
                        </td>
                        <td>{{ \Illuminate\Support\Str::limit($cita->mtv_cit, 30) }}</td>
                        <td>
                            @if($cita->id_eci == 1 || $cita->id_eci == 2)
                            <a href="{{ route('doctor.consultas.create', ['cita_id' => $cita->id_cit, 'paciente_ced' => $cita->ced_pac]) }}" class="btn-primary" style="font-size: 12px; padding: 5px 10px; display: inline-flex; align-items: center; gap: 5px;">
                                <ion-icon name="medical-outline"></ion-icon> Registrar Consulta
                            </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #666;">No tiene citas asignadas a su nombre.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>
</div>
@endsection

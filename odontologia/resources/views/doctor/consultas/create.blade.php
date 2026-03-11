@extends('layouts.app')

@section('contenido')
<div class="maintenance-view">
    <section class="form-container">
        <h2>Registrar Nueva Consulta</h2>
        <p>Complete los datos de la evaluación clínica.</p>

        <form action="{{ route('doctor.consultas.store') }}" method="POST" class="modern-form" style="margin-top: 20px;">
            @csrf
            
            @if($citaId)
                <input type="hidden" name="cita_id" value="{{ $citaId }}">
                <div class="alert alert-info" style="margin-bottom: 20px; padding: 10px; background: #e7f3fe; border-left: 4px solid #0078d4;">
                    Esta consulta está enlazada a una cita programada.
                </div>
            @endif

            <div class="form-grid">
                <div>
                    <label style="display:block; margin-bottom: 5px; color: #666;">Paciente</label>
                    <select name="ced_pac" required>
                        <option value="">Seleccione un paciente...</option>
                        @foreach($pacientes as $paciente)
                            <option value="{{ $paciente->ced_pac }}" {{ $pacienteCed == $paciente->ced_pac ? 'selected' : '' }}>
                                {{ $paciente->nom_pac }} {{ $paciente->ape_pac }} ({{ $paciente->ced_pac }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label style="display:block; margin-bottom: 5px; color: #666;">Fecha y Hora</label>
                    <input type="datetime-local" name="fec_con" value="{{ \Carbon\Carbon::now()->format('Y-m-d\TH:i') }}" required>
                </div>

                <div style="grid-column: 1 / -1;">
                    <label style="display:block; margin-bottom: 5px; color: #666;">Motivo de Consulta</label>
                    <input type="text" name="motivo" placeholder="Ej. Dolor agudo, limpieza de rutina..." required>
                </div>

                <div style="grid-column: 1 / -1;">
                    <label style="display:block; margin-bottom: 5px; color: #666;">Observaciones Médicas</label>
                    <textarea name="observaciones" rows="5" placeholder="Detalle los hallazgos médicos y evaluación clínica..." style="width: 100%; padding: 12px; border: 1px solid var(--ms-border); border-radius: 4px; font-family: inherit; resize: vertical;" required></textarea>
                </div>
            </div>

            <div class="form-actions" style="margin-top: 20px;">
                <button type="submit" class="btn-primary">Guardar Consulta</button>
                <a href="{{ $citaId ? route('doctor.citas.index') : route('doctor.consultas.index') }}" class="btn-secondary">Cancelar</a>
            </div>
        </form>
    </section>
</div>
@endsection

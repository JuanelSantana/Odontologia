@extends('layouts.app')

@section('contenido')
<div class="maintenance-view">
    <section class="form-container">
        <h2>Editar Consulta #{{ $consulta->id_con }}</h2>

        <form action="{{ route('doctor.consultas.update', $consulta->id_con) }}" method="POST" class="modern-form" style="margin-top: 20px;">
            @csrf
            @method('PUT')
            
            <div class="form-grid">
                <div>
                    <label style="display:block; margin-bottom: 5px; color: #666;">Paciente</label>
                    <select name="ced_pac" required>
                        @foreach($pacientes as $paciente)
                            <option value="{{ $paciente->ced_pac }}" {{ $consulta->ced_pac == $paciente->ced_pac ? 'selected' : '' }}>
                                {{ $paciente->nom_pac }} {{ $paciente->ape_pac }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label style="display:block; margin-bottom: 5px; color: #666;">Fecha y Hora</label>
                    <input type="datetime-local" name="fec_con" value="{{ \Carbon\Carbon::parse($consulta->fec_con)->format('Y-m-d\TH:i') }}" required>
                </div>

                <div style="grid-column: 1 / -1;">
                    <label style="display:block; margin-bottom: 5px; color: #666;">Motivo de Consulta</label>
                    <input type="text" name="motivo" value="{{ $consulta->motivo }}" required>
                </div>

                <div style="grid-column: 1 / -1;">
                    <label style="display:block; margin-bottom: 5px; color: #666;">Observaciones Médicas</label>
                    <textarea name="observaciones" rows="5" style="width: 100%; padding: 12px; border: 1px solid var(--ms-border); border-radius: 4px; font-family: inherit; resize: vertical;" required>{{ $consulta->observaciones }}</textarea>
                </div>
            </div>

            <div class="form-actions" style="margin-top: 20px;">
                <button type="submit" class="btn-primary">Guardar Cambios</button>
                <a href="{{ route('doctor.consultas.index') }}" class="btn-secondary">Cancelar</a>
            </div>
        </form>
    </section>
</div>
@endsection

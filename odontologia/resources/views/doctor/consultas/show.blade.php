@extends('layouts.app')

@section('contenido')
<div class="maintenance-view">
    <section class="form-container" style="background: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); padding: 30px;">
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 30px; border-bottom: 1px solid #eee; padding-bottom: 20px;">
            <div>
                <h2 style="margin:0; color: #323130; display: flex; align-items: center; gap: 10px;">
                    <ion-icon name="document-text" style="color: #0078d4;"></ion-icon> Consulta Médica
                </h2>
                <span style="color: #605e5d; font-size: 14px;">Realizada el {{ \Carbon\Carbon::parse($consulta->fec_con)->format('d \d\e F, Y \a \l\a\s h:i A') }}</span>
            </div>
            <div>
                <a href="{{ route('doctor.consultas.index') }}" class="btn-secondary" style="padding: 6px 15px;">Volver</a>
                <a href="{{ route('doctor.consultas.edit', $consulta->id_con) }}" class="btn-primary" style="padding: 6px 15px;">Editar</a>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 40px;">
            <!-- Información del Paciente -->
            <div style="background: #f8f9fa; padding: 20px; border-radius: 6px; border: 1px solid #e9ecef;">
                <h3 style="margin-top:0; font-size: 16px; color: #495057; border-bottom: 2px solid #0078d4; padding-bottom: 10px; display: inline-block;">Datos del Paciente</h3>
                
                <div style="margin-bottom: 15px;">
                    <span style="display:block; color: #6c757d; font-size: 12px; text-transform: uppercase;">Nombre Completo</span>
                    <strong style="font-size: 16px; color: #212529;">{{ $consulta->paciente->nom_pac }} {{ $consulta->paciente->ape_pac }}</strong>
                </div>

                <div style="margin-bottom: 15px;">
                    <span style="display:block; color: #6c757d; font-size: 12px; text-transform: uppercase;">Cédula</span>
                    <span style="color: #495057;">{{ $consulta->paciente->ced_pac }}</span>
                </div>

                <div style="margin-bottom: 15px;">
                    <span style="display:block; color: #6c757d; font-size: 12px; text-transform: uppercase;">Teléfono</span>
                    <span style="color: #495057;">{{ $consulta->paciente->tel_pac }}</span>
                </div>

                <div>
                    <span style="display:block; color: #6c757d; font-size: 12px; text-transform: uppercase;">Correo</span>
                    <span style="color: #495057;">{{ $consulta->paciente->eml_pac }}</span>
                </div>
            </div>

            <!-- Información Clínica -->
            <div>
                <div style="margin-bottom: 30px;">
                    <h3 style="margin-top:0; font-size: 16px; color: #495057; border-bottom: 2px solid #107c10; padding-bottom: 10px; display: inline-block;">Motivo de Consulta</h3>
                    <p style="font-size: 16px; color: #212529; background: #fff; padding: 15px; border-left: 4px solid #107c10; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                        {{ $consulta->motivo }}
                    </p>
                </div>

                <div>
                    <h3 style="font-size: 16px; color: #495057; border-bottom: 2px solid #d83b01; padding-bottom: 10px; display: inline-block;">Observaciones Médicas</h3>
                    <div style="color: #212529; background: #fff; padding: 20px; border: 1px solid #e9ecef; border-radius: 4px; line-height: 1.6; white-space: pre-wrap;">{{ $consulta->observaciones }}</div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@extends('layouts.app')

@section('contenido')
    <div class="maintenance-view" style="margin-top: 80px;">
        <section class="form-container">
            <h2>{{ $tratamientoEdit ? 'Editar Tratamiento' : 'Nuevo Tratamiento' }}</h2>

            <form
                action="{{ $tratamientoEdit ? route('doctor.tratamientos.update', $tratamientoEdit->id_tra) : route('doctor.tratamientos.store') }}"
                method="POST" class="modern-form">
                @csrf
                @if($tratamientoEdit) @method('PUT') @endif

                <div class="form-grid">
                    <select name="ced_pac" required>
                        <option value="">Seleccione Paciente...</option>
                        @foreach($pacientes as $pac)
                            <option value="{{ $pac->ced_pac }}" {{ ($tratamientoEdit && $tratamientoEdit->ced_pac == $pac->ced_pac) ? 'selected' : '' }}>
                                {{ $pac->nom_pac }} {{ $pac->ape_pac }} ({{ $pac->ced_pac }})
                            </option>
                        @endforeach
                    </select>

                    <input type="text" name="nom_tra" placeholder="Nombre Tratamiento (Opcional)"
                        value="{{ $tratamientoEdit->nom_tra ?? '' }}">

                    <select name="id_ttr" required>
                        <option value="">Tipo Tratamiento...</option>
                        @foreach($tipos as $tipo)
                            <option value="{{ $tipo->id_ttr }}" {{ ($tratamientoEdit && $tratamientoEdit->id_ttr == $tipo->id_ttr) ? 'selected' : '' }}>
                                {{ $tipo->nom_ttr }}
                            </option>
                        @endforeach
                    </select>

                    <select name="id_srv" required>
                        <option value="">Servicio Base...</option>
                        @foreach($servicios as $srv)
                            <option value="{{ $srv->id_srv }}" {{ ($tratamientoEdit && $tratamientoEdit->id_srv == $srv->id_srv) ? 'selected' : '' }}>
                                {{ $srv->nom_srv }} (${{ number_format($srv->cst_srv, 2) }})
                            </option>
                        @endforeach
                    </select>

                    <input type="number" step="0.01" name="cst_tra" placeholder="Costo Total"
                        value="{{ $tratamientoEdit->cst_tra ?? '' }}" required>

                    <div>
                        <label style="font-size: 12px; color: #666;">Fecha Inicio</label>
                        <input type="date" name="fec_ini_tra" value="{{ $tratamientoEdit->fec_ini_tra ?? date('Y-m-d') }}"
                            required style="width: 100%;">
                    </div>

                    <div>
                        <label style="font-size: 12px; color: #666;">Fecha Fin (Aprox/Real)</label>
                        <input type="date" name="fec_fin_tra" value="{{ $tratamientoEdit->fec_fin_tra ?? '' }}"
                            style="width: 100%;">
                    </div>

                    <input type="text" name="dur_tra" placeholder="Duración Estimada (ej. 3 meses)"
                        value="{{ $tratamientoEdit->dur_tra ?? '' }}">

                    <select name="id_cit">
                        <option value="">Vincular a Cita (Opcional)...</option>
                        @foreach($citas as $cita)
                            <option value="{{ $cita->id_cit }}" {{ ($tratamientoEdit && $tratamientoEdit->id_cit == $cita->id_cit) ? 'selected' : '' }}>
                                Cita #{{ $cita->id_cit }} - {{ \Carbon\Carbon::parse($cita->fec_cit)->format('d/m/Y') }}
                            </option>
                        @endforeach
                    </select>

                    <div style="grid-column: 1 / -1;">
                        <textarea name="dsc_tra" rows="3" placeholder="Descripción detallada del tratamiento..."
                            style="width: 100%; padding: 10px; border: 1px solid var(--ms-border); border-radius: 4px; font-family: inherit;">{{ $tratamientoEdit->dsc_tra ?? '' }}</textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit"
                        class="btn-primary">{{ $tratamientoEdit ? 'Guardar Cambios' : 'Registrar Tratamiento' }}</button>
                    @if($tratamientoEdit)
                        <a href="{{ route('doctor.tratamientos.index') }}" class="btn-secondary">Cancelar</a>
                    @endif
                </div>
            </form>
        </section>

        <hr>

        <section class="table-container">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Paciente</th>
                        <th>Tratamiento</th>
                        <th>Inicio</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tratamientos as $item)
                        <tr>
                            <td>{{ $item->id_tra }}</td>
                            <td>{{ $item->paciente->nom_pac }} {{ $item->paciente->ape_pac }}</td>
                            <td>
                                @if($item->nom_tra)
                                    <strong>{{ $item->nom_tra }}</strong><br>
                                @endif
                                <span style="font-size: 11px; color: #666;">{{ $item->tipo->nom_ttr ?? 'N/A' }}</span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($item->fec_ini_tra)->format('d/m/Y') }}</td>
                            <td>
                                @if($item->fec_fin_tra && \Carbon\Carbon::parse($item->fec_fin_tra)->isPast())
                                    <span class="badge"
                                        style="background-color: #28a745; color: white; padding: 3px 6px; border-radius: 10px; font-size: 11px;">Finalizado</span>
                                @else
                                    <span class="badge"
                                        style="background-color: #0078d4; color: white; padding: 3px 6px; border-radius: 10px; font-size: 11px;">En
                                        Proceso</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('doctor.tratamientos.index', ['edit' => $item->id_tra]) }}"
                                    class="btn-icon select" title="Editar">
                                    <ion-icon name="create-outline"></ion-icon>
                                </a>
                                <form action="{{ route('doctor.tratamientos.destroy', $item->id_tra) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon delete"
                                        onclick="return confirm('¿Eliminar este registro seguro?')" title="Eliminar">
                                        <ion-icon name="trash-outline"></ion-icon>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    </div>
@endsection
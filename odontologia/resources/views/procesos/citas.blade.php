@extends('procesos.procesos')

@section('contenidoproceso')
    <div class="maintenance-view">
        <section class="form-container">
            <h2>{{ $citaEdit ? 'Editar Cita' : 'Nueva Cita' }}</h2>

            <form
                action="{{ $citaEdit ? route('procesos.citas.update', $citaEdit->id_cit) : route('procesos.citas.store') }}"
                method="POST" class="modern-form">
                @csrf
                @if($citaEdit) @method('PUT') @endif

                <div class="form-grid">
                    <div class="form-group">
                        <label>Paciente</label>
                        <select name="ced_pac" required>
                            <option value="">Seleccione Paciente...</option>
                            @foreach($pacientes as $pac)
                                <option value="{{ $pac->ced_pac }}" {{ ($citaEdit && $citaEdit->ced_pac == $pac->ced_pac) ? 'selected' : '' }}>
                                    {{ $pac->nom_pac }} {{ $pac->ape_pac }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Doctor</label>
                        <select name="id_doc" required>
                            <option value="">Seleccione Doctor...</option>
                            @foreach($doctores as $doc)
                                <option value="{{ $doc->id_doc }}" {{ ($citaEdit && $citaEdit->id_doc == $doc->id_doc) ? 'selected' : '' }}>
                                    Dr. {{ $doc->nom_doc }} {{ $doc->ape_doc }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Estado</label>
                        <select name="id_eci" required>
                            @foreach($estados as $est)
                                <option value="{{ $est->id_eci }}" {{ ($citaEdit && $citaEdit->id_eci == $est->id_eci) ? 'selected' : ($est->id_eci == 1 ? 'selected' : '') }}>
                                    {{ $est->nom_eci }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Fecha y Hora</label>
                        <input type="datetime-local" name="fec_cit"
                            value="{{ $citaEdit ? date('Y-m-d\TH:i', strtotime($citaEdit->fec_cit)) : '' }}" required>
                    </div>

                    <div class="form-group" style="grid-column: span 2;">
                        <label>Motivo</label>
                        <input type="text" name="mtv_cit" placeholder="Motivo de la cita" value="{{ $citaEdit->mtv_cit ?? '' }}">
                    </div>

                    <div class="form-group" style="grid-column: span 2;">
                        <label>Servicios (Selección Múltiple)</label>
                        <div class="checkbox-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; background: #f9f9f9; padding: 10px; border-radius: 8px;">
                            @foreach($servicios as $srv)
                                <label style="display: flex; align-items: center; gap: 5px; font-size: 0.9rem;">
                                    <input type="checkbox" name="id_srv[]" value="{{ $srv->id_srv }}"
                                        {{ ($citaEdit && $citaEdit->servicios->contains($srv->id_srv)) ? 'checked' : '' }}>
                                    {{ $srv->nom_srv }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">{{ $citaEdit ? 'Guardar Cambios' : 'Agendar' }}</button>
                    @if($citaEdit)
                        <a href="{{ route('procesos.citas.index') }}" class="btn-secondary">Cancelar</a>
                    @endif
                </div>
            </form>
        </section>

        <hr>

        <section class="table-container">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Paciente</th>
                        <th>Doctor</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($citas as $item)
                        <tr>
                            <td>{{ date('d/m/Y H:i', strtotime($item->fec_cit)) }}</td>
                            <td>{{ $item->paciente->nom_pac ?? 'N/A' }} {{ $item->paciente->ape_pac ?? '' }}</td>
                            <td>Dr. {{ $item->doctor->nom_doc ?? 'N/A' }}</td>
                            <td>
                                <span class="badge status-{{ strtolower($item->estado->nom_eci ?? 'pendiente') }}" style="padding: 5px 10px; border-radius: 20px; font-size: 0.8rem; background: #eee;">
                                    {{ $item->estado->nom_eci ?? 'Pendiente' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('procesos.citas.index', ['edit' => $item->id_cit]) }}"
                                    class="btn-icon select">
                                    <ion-icon name="create-outline"></ion-icon>
                                </a>

                                <form action="{{ route('procesos.citas.destroy', $item->id_cit) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon delete" onclick="return confirm('¿Eliminar esta cita?')">
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

@extends('mantenimientos.mantenimientos')

@section('contenidomant')
    <div class="maintenance-view">
        <section class="form-container">
            <h2>{{ $tratamientoEdit ? 'Editar Tratamiento' : 'Nuevo Tratamiento' }}</h2>

            <form
                action="{{ $tratamientoEdit ? route('mantenimientos.tratamientos.update', $tratamientoEdit->id_tra) : route('mantenimientos.tratamientos.store') }}"
                method="POST" class="modern-form">
                @csrf
                @if($tratamientoEdit) @method('PUT') @endif

                <div class="form-grid">
                    <select name="ced_pac" required>
                        <option value="">Seleccione Paciente...</option>
                        @foreach($pacientes as $pac)
                            <option value="{{ $pac->ced_pac }}" 
                                {{ ($tratamientoEdit && $tratamientoEdit->ced_pac == $pac->ced_pac) ? 'selected' : '' }}>
                                {{ $pac->nom_pac }} {{ $pac->ape_pac }}
                            </option>
                        @endforeach
                    </select>

                    <select name="id_doc" required>
                        <option value="">Seleccione Doctor...</option>
                        @foreach($doctores as $doc)
                            <option value="{{ $doc->id_doc }}" 
                                {{ ($tratamientoEdit && $tratamientoEdit->id_doc == $doc->id_doc) ? 'selected' : '' }}>
                                {{ $doc->nom_doc }} {{ $doc->ape_doc }}
                            </option>
                        @endforeach
                    </select>

                    <select name="id_ttr" required>
                        <option value="">Seleccione Tipo Tratamiento...</option>
                        @foreach($tipos as $tipo)
                            <option value="{{ $tipo->id_ttr }}" 
                                {{ ($tratamientoEdit && $tratamientoEdit->id_ttr == $tipo->id_ttr) ? 'selected' : '' }}>
                                {{ $tipo->nom_ttr }}
                            </option>
                        @endforeach
                    </select>

                    <select name="id_srv" required>
                        <option value="">Seleccione Servicio...</option>
                        @foreach($servicios as $srv)
                            <option value="{{ $srv->id_srv }}" 
                                {{ ($tratamientoEdit && $tratamientoEdit->id_srv == $srv->id_srv) ? 'selected' : '' }}>
                                {{ $srv->nom_srv }}
                            </option>
                        @endforeach
                    </select>

                    <input type="text" name="nom_tra" placeholder="Nombre (Ej. Limpieza General)" value="{{ $tratamientoEdit->nom_tra ?? '' }}">
                    <input type="text" name="dsc_tra" placeholder="Descripción" value="{{ $tratamientoEdit->dsc_tra ?? '' }}">
                    <input type="number" step="0.01" name="cst_tra" placeholder="Costo Tratamiento" value="{{ $tratamientoEdit->cst_tra ?? '' }}" required>
                    <div style="display:flex; flex-direction: column;">
                        <label style="font-size: 0.8rem; margin-bottom: 2px;">Fecha Inicio</label>
                        <input type="date" name="fec_ini_tra" value="{{ isset($tratamientoEdit->fec_ini_tra) ? \Carbon\Carbon::parse($tratamientoEdit->fec_ini_tra)->format('Y-m-d') : '' }}" required>
                    </div>
                    <div style="display:flex; flex-direction: column;">
                        <label style="font-size: 0.8rem; margin-bottom: 2px;">Fecha Fin (Opcional)</label>
                        <input type="date" name="fec_fin_tra" value="{{ isset($tratamientoEdit->fec_fin_tra) ? \Carbon\Carbon::parse($tratamientoEdit->fec_fin_tra)->format('Y-m-d') : '' }}">
                    </div>
                    
                    <input type="text" name="dur_tra" placeholder="Duración (Ej. 2 meses)" value="{{ $tratamientoEdit->dur_tra ?? '' }}">
                    
                    <select name="id_cit">
                        <option value="">Relacionar a Cita (Opcional)...</option>
                        @foreach($citas as $cit)
                            <option value="{{ $cit->id_cit }}" 
                                {{ ($tratamientoEdit && $tratamientoEdit->id_cit == $cit->id_cit) ? 'selected' : '' }}>
                                Cita #{{ $cit->id_cit }} - {{ \Carbon\Carbon::parse($cit->fec_cit)->format('d/m/Y') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit"
                        class="btn-primary">{{ $tratamientoEdit ? 'Guardar Cambios' : 'Insertar' }}</button>
                    @if($tratamientoEdit)
                        <a href="{{ route('mantenimientos.tratamientos.index') }}" class="btn-secondary">Cancelar</a>
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
                        <th>Nombre</th>
                        <th>Paciente</th>
                        <th>Doctor</th>
                        <th>Servicio</th>
                        <th>Costo</th>
                        <th>Inicio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tratamientos as $item)
                        <tr>
                            <td>{{ $item->id_tra }}</td>
                            <td>{{ $item->nom_tra ?? 'N/A' }}</td>
                            <td>{{ $item->paciente->nom_pac ?? 'N/A' }} {{ $item->paciente->ape_pac ?? '' }}</td>
                            <td>{{ $item->doctor->nom_doc ?? 'N/A' }} {{ $item->doctor->ape_doc ?? '' }}</td>
                            <td>{{ $item->servicio->nom_srv ?? 'N/A' }}</td>
                            <td>${{ number_format($item->cst_tra, 2) }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->fec_ini_tra)->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('mantenimientos.tratamientos.index', ['edit' => $item->id_tra]) }}"
                                    class="btn-icon select">
                                    <ion-icon name="create-outline"></ion-icon>
                                </a>

                                <form action="{{ route('mantenimientos.tratamientos.destroy', $item->id_tra) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon delete" onclick="return confirm('¿Eliminar?')">
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

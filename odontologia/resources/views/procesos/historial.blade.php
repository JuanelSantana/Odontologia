@extends('procesos.procesos')

@section('contenidoproceso')
    <div class="maintenance-view">
        <section class="form-container">
            <h2>{{ $historialEdit ? 'Editar Historia Clínica' : 'Nueva Historia Clínica' }}</h2>

            <form
                action="{{ $historialEdit ? route('procesos.historial.update', $historialEdit->id_hcl) : route('procesos.historial.store') }}"
                method="POST" class="modern-form">
                @csrf
                @if($historialEdit) @method('PUT') @endif

                <div class="form-grid">
                    <select name="ced_pac" required>
                        <option value="">Seleccione Paciente...</option>
                        @foreach($pacientes as $pac)
                            <option value="{{ $pac->ced_pac }}" {{ ($historialEdit && $historialEdit->ced_pac == $pac->ced_pac) ? 'selected' : '' }}>
                                {{ $pac->nom_pac }} {{ $pac->ape_pac }} ({{ $pac->ced_pac }})
                            </option>
                        @endforeach
                    </select>

                    <textarea name="dig_hcl" placeholder="Diagnóstico Inicial" required>{{ $historialEdit->dig_hcl ?? '' }}</textarea>
                    <textarea name="trt_prev_hcl" placeholder="Tratamientos Previos">{{ $historialEdit->trt_prev_hcl ?? '' }}</textarea>
                    <textarea name="alg_hcl" placeholder="Alergias">{{ $historialEdit->alg_hcl ?? '' }}</textarea>
                    <textarea name="mds_hcl" placeholder="Medicamentos actuales">{{ $historialEdit->mds_hcl ?? '' }}</textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">{{ $historialEdit ? 'Guardar Cambios' : 'Insertar' }}</button>
                    @if($historialEdit)
                        <a href="{{ route('procesos.historial.index') }}" class="btn-secondary">Cancelar</a>
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
                        <th>Diagnóstico</th>
                        <th>Alergias</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($historiales as $item)
                        <tr>
                            <td>{{ $item->id_hcl }}</td>
                            <td>{{ $item->paciente->nom_pac ?? 'N/A' }} {{ $item->paciente->ape_pac ?? '' }}</td>
                            <td>{{ Str::limit($item->dig_hcl, 50) }}</td>
                            <td>{{ $item->alg_hcl ?? 'Ninguna' }}</td>
                            <td>
                                <a href="{{ route('procesos.historial.index', ['edit' => $item->id_hcl]) }}"
                                    class="btn-icon select">
                                    <ion-icon name="create-outline"></ion-icon>
                                </a>

                                <form action="{{ route('procesos.historial.destroy', $item->id_hcl) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon delete" onclick="return confirm('¿Eliminar esta historia clínica?')">
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

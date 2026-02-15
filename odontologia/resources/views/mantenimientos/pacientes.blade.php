@extends('mantenimientos.mantenimientos')

@section('contenidomant')
    <div class="maintenance-view">
        <section class="form-container">
            <h2>{{ $pacienteEdit ? 'Editar Paciente' : 'Nuevo Paciente' }}</h2>

            <form
                action="{{ $pacienteEdit ? route('mantenimientos.pacientes.update', $pacienteEdit->id_pac) : route('mantenimientos.pacientes.store') }}"
                method="POST" class="modern-form">
                @csrf
                @if($pacienteEdit) @method('PUT') @endif

                <div class="form-grid">
                    <input type="text" name="nom_pac" placeholder="Nombres" value="{{ $pacienteEdit->nom_pac ?? '' }}"
                        required>
                    <input type="text" name="ape_pac" placeholder="Apellidos" value="{{ $pacienteEdit->ape_pac ?? '' }}"
                        required>
                    <input type="text" name="ced_pac" placeholder="Cédula" value="{{ $pacienteEdit->ced_pac ?? '' }}"
                        required>
                    <select name="gen_pac" required>
                        <option value="">Género</option>
                        <option value="Masculino" {{ (isset($pacienteEdit) && $pacienteEdit->gen_pac == 'Masculino') ? 'selected' : '' }}>Masculino</option>
                        <option value="Femenino" {{ (isset($pacienteEdit) && $pacienteEdit->gen_pac == 'Femenino') ? 'selected' : '' }}>Femenino</option>
                        <option value="Otro" {{ (isset($pacienteEdit) && $pacienteEdit->gen_pac == 'Otro') ? 'selected' : '' }}>Otro</option>
                    </select>
                    <input type="date" name="fec_nac_pac" value="{{ $pacienteEdit->fec_nac_pac ?? '' }}" required>
                    <input type="text" name="tel_pac" placeholder="Teléfono" value="{{ $pacienteEdit->tel_pac ?? '' }}"
                        required>
                    <input type="email" name="eml_pac" placeholder="Correo" value="{{ $pacienteEdit->eml_pac ?? '' }}"
                        required>
                    <input type="text" name="tip_pac" placeholder="Tipo (Ej: Regular)"
                        value="{{ $pacienteEdit->tip_pac ?? '' }}">
                    <select name="id_seg">
                        <option value="">Sin Seguro</option>
                        @foreach($seguros as $seguro)
                            <option value="{{ $seguro->id_seg }}" {{ (isset($pacienteEdit) && $pacienteEdit->id_seg == $seguro->id_seg) ? 'selected' : '' }}>
                                {{ $seguro->nom_seg }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <textarea name="cnd_sal_pac"
                    placeholder="Condición de Salud">{{ $pacienteEdit->cnd_sal_pac ?? '' }}</textarea>

                <div class="form-actions">
                    <button type="submit"
                        class="btn-primary">{{ $pacienteEdit ? 'Guardar Cambios' : 'Registrar Paciente' }}</button>
                    @if($pacienteEdit)
                        <a href="{{ route('mantenimientos.pacientes.index') }}" class="btn-secondary">Cancelar</a>
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
                        <th>Cédula</th>
                        <th>Teléfono</th>
                        <th>Seguro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pacientes as $item)
                        <tr>
                            <td>{{ $item->id_pac }}</td>
                            <td>{{ $item->nom_pac }} {{ $item->ape_pac }}</td>
                            <td>{{ $item->ced_pac }}</td>
                            <td>{{ $item->tel_pac }}</td>
                            <td>{{ $item->seguro->nom_seg ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('mantenimientos.pacientes.index', ['edit' => $item->id_pac]) }}"
                                    class="btn-icon select">
                                    <ion-icon name="create-outline"></ion-icon>
                                </a>

                                <form action="{{ route('mantenimientos.pacientes.destroy', $item->id_pac) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon delete"
                                        onclick="return confirm('¿Eliminar paciente?')">
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
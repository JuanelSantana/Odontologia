@extends('mantenimientos.mantenimientos')

@section('contenidomant')
    <div class="maintenance-view">
        <section class="form-container">
            <h2>{{ $doctorEdit ? 'Editar Doctor' : 'Nuevo Doctor' }}</h2>

            <form
                action="{{ $doctorEdit ? route('mantenimientos.doctores.update', $doctorEdit->id_doc) : route('mantenimientos.doctores.store') }}"
                method="POST" class="modern-form">
                @csrf
                @if($doctorEdit) @method('PUT') @endif

                <div class="form-grid">
                    <input type="text" name="nom_doc" placeholder="Nombre (Ej. Juan)"
                        value="{{ $doctorEdit->nom_doc ?? '' }}" required>
                    <input type="text" name="ape_doc" placeholder="Apellidos (Ej. Perez)"
                        value="{{ $doctorEdit->ape_doc ?? '' }}" required>
                    <input type="text" name="ced_doc" placeholder="Cédula" value="{{ $doctorEdit->ced_doc ?? '' }}">
                    <input type="text" name="tel_doc" placeholder="Teléfono" value="{{ $doctorEdit->tel_doc ?? '' }}">
                    <input type="email" name="eml_doc" placeholder="Email" value="{{ $doctorEdit->eml_doc ?? '' }}">

                    <select name="id_esp" required>
                        <option value="">Seleccione Especialidad...</option>
                        @foreach($especialidades as $esp)
                            <option value="{{ $esp->id_esp }}" {{ ($doctorEdit && $doctorEdit->id_esp == $esp->id_esp) ? 'selected' : '' }}>
                                {{ $esp->nom_esp }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">{{ $doctorEdit ? 'Guardar Cambios' : 'Insertar' }}</button>
                    @if($doctorEdit)
                        <a href="{{ route('mantenimientos.doctores.index') }}" class="btn-secondary">Cancelar</a>
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
                        <th>Cédula</th>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Especialidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($doctores as $item)
                        <tr>
                            <td>{{ $item->id_doc }}</td>
                            <td>{{ $item->ced_doc }}</td>
                            <td>{{ $item->nom_doc }} {{ $item->ape_doc }}</td>
                            <td>{{ $item->tel_doc }}</td>
                            <td>{{ $item->eml_doc }}</td>
                            <td>{{ $item->especialidad->nom_esp ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('mantenimientos.doctores.index', ['edit' => $item->id_doc]) }}"
                                    class="btn-icon select">
                                    <ion-icon name="create-outline"></ion-icon>
                                </a>

                                <form action="{{ route('mantenimientos.doctores.destroy', $item->id_doc) }}" method="POST"
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
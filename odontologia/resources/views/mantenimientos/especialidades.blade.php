@extends('mantenimientos.mantenimientos')

@section('contenidomant')
    <div class="maintenance-view">
        <section class="form-container">
            <h2>{{ $especialidadEdit ? 'Editar Especialidad' : 'Nueva Especialidad' }}</h2>

            <form
                action="{{ $especialidadEdit ? route('mantenimientos.especialidades.update', $especialidadEdit->id_esp) : route('mantenimientos.especialidades.store') }}"
                method="POST" class="modern-form">
                @csrf
                @if($especialidadEdit) @method('PUT') @endif

                <div class="form-grid">
                    <input type="text" name="nom_esp" placeholder="Nombre" value="{{ $especialidadEdit->nom_esp ?? '' }}"
                        required>
                    <input type="text" name="dsc_esp" placeholder="Descripción"
                        value="{{ $especialidadEdit->dsc_esp ?? '' }}">
                </div>

                <div class="form-actions">
                    <button type="submit"
                        class="btn-primary">{{ $especialidadEdit ? 'Guardar Cambios' : 'Insertar' }}</button>
                    @if($especialidadEdit)
                        <a href="{{ route('mantenimientos.especialidades.index') }}" class="btn-secondary">Cancelar</a>
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
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($especialidades as $item)
                        <tr>
                            <td>{{ $item->id_esp }}</td>
                            <td>{{ $item->nom_esp }}</td>
                            <td>{{ $item->dsc_esp }}</td>
                            <td>
                                <a href="{{ route('mantenimientos.especialidades.index', ['edit' => $item->id_esp]) }}"
                                    class="btn-icon select">
                                    <ion-icon name="create-outline"></ion-icon>
                                </a>

                                <form action="{{ route('mantenimientos.especialidades.destroy', $item->id_esp) }}" method="POST"
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
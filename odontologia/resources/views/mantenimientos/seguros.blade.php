@extends('mantenimientos.mantenimientos')

@section('contenidomant')
    <div class="maintenance-view">
        <section class="form-container">
            <h2>{{ $seguroEdit ? 'Editar Seguro' : 'Nuevo Seguro' }}</h2>

            <form
                action="{{ $seguroEdit ? route('mantenimientos.seguros.update', $seguroEdit->id_seg) : route('mantenimientos.seguros.store') }}"
                method="POST" class="modern-form">
                @csrf
                @if($seguroEdit) @method('PUT') @endif

                <div class="form-grid">
                    <input type="text" name="nom_seg" placeholder="Nombre Seguro" value="{{ $seguroEdit->nom_seg ?? '' }}"
                        required>
                    <input type="text" name="tel_seg" placeholder="Teléfono" value="{{ $seguroEdit->tel_seg ?? '' }}">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">{{ $seguroEdit ? 'Guardar Cambios' : 'Insertar' }}</button>
                    @if($seguroEdit)
                        <a href="{{ route('mantenimientos.seguros.index') }}" class="btn-secondary">Cancelar</a>
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
                        <th>Teléfono</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($seguros as $item)
                        <tr>
                            <td>{{ $item->id_seg }}</td>
                            <td>{{ $item->nom_seg }}</td>
                            <td>{{ $item->tel_seg }}</td>
                            <td>
                                <a href="{{ route('mantenimientos.seguros.index', ['edit' => $item->id_seg]) }}"
                                    class="btn-icon select">
                                    <ion-icon name="create-outline"></ion-icon>
                                </a>

                                <form action="{{ route('mantenimientos.seguros.destroy', $item->id_seg) }}" method="POST"
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
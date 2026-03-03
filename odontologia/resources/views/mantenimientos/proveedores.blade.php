@extends('mantenimientos.mantenimientos')

@section('contenidomant')
    <div class="maintenance-view">
        <section class="form-container">
            <h2>{{ $proveedorEdit ? 'Editar Proveedor' : 'Nuevo Proveedor' }}</h2>

            <form
                action="{{ $proveedorEdit ? route('mantenimientos.proveedores.update', $proveedorEdit->id_prv) : route('mantenimientos.proveedores.store') }}"
                method="POST" class="modern-form">
                @csrf
                @if($proveedorEdit) @method('PUT') @endif

                <div class="form-grid">
                    <input type="text" name="nom_prv" placeholder="Nombre Proveedor"
                        value="{{ $proveedorEdit->nom_prv ?? '' }}" required>
                    <input type="text" name="loc_prv" placeholder="Localidad/Dirección"
                        value="{{ $proveedorEdit->loc_prv ?? '' }}">
                    <input type="text" name="tel_prv" placeholder="Teléfono" value="{{ $proveedorEdit->tel_prv ?? '' }}">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">{{ $proveedorEdit ? 'Guardar Cambios' : 'Insertar' }}</button>
                    @if($proveedorEdit)
                        <a href="{{ route('mantenimientos.proveedores.index') }}" class="btn-secondary">Cancelar</a>
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
                        <th>Localidad</th>
                        <th>Teléfono</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($proveedores as $item)
                        <tr>
                            <td>{{ $item->id_prv }}</td>
                            <td>{{ $item->nom_prv }}</td>
                            <td>{{ $item->loc_prv }}</td>
                            <td>{{ $item->tel_prv }}</td>
                            <td>
                                <a href="{{ route('mantenimientos.proveedores.index', ['edit' => $item->id_prv]) }}"
                                    class="btn-icon select">
                                    <ion-icon name="create-outline"></ion-icon>
                                </a>

                                <form action="{{ route('mantenimientos.proveedores.destroy', $item->id_prv) }}" method="POST"
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
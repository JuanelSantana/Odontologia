@extends('mantenimientos.mantenimientos')

@section('contenidomant')
    <div class="maintenance-view">
        <section class="form-container">
            <h2>{{ $inventarioEdit ? 'Editar Inventario' : 'Nuevo Registro de Inventario' }}</h2>

            <form
                action="{{ $inventarioEdit ? route('mantenimientos.inventario.update', $inventarioEdit->id_inv) : route('mantenimientos.inventario.store') }}"
                method="POST" class="modern-form">
                @csrf
                @if($inventarioEdit) @method('PUT') @endif

                <div class="form-grid">
                    <select name="id_mat" required>
                        <option value="">Seleccione Material...</option>
                        @foreach($materiales as $mat)
                            <option value="{{ $mat->id_mat }}" {{ ($inventarioEdit && $inventarioEdit->id_mat == $mat->id_mat) ? 'selected' : '' }}>
                                {{ $mat->nom_mat }}
                            </option>
                        @endforeach
                    </select>

                    <select name="id_prv" required>
                        <option value="">Seleccione Proveedor...</option>
                        @foreach($proveedores as $prv)
                            <option value="{{ $prv->id_prv }}" {{ ($inventarioEdit && $inventarioEdit->id_prv == $prv->id_prv) ? 'selected' : '' }}>
                                {{ $prv->nom_prv }}
                            </option>
                        @endforeach
                    </select>

                    <input type="number" name="cnt_inv" placeholder="Cantidad en Stock"
                        value="{{ $inventarioEdit->cnt_inv ?? '' }}" required min="0">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">{{ $inventarioEdit ? 'Guardar Cambios' : 'Insertar' }}</button>
                    @if($inventarioEdit)
                        <a href="{{ route('mantenimientos.inventario.index') }}" class="btn-secondary">Cancelar</a>
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
                        <th>Material</th>
                        <th>Proveedor</th>
                        <th>Cantidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inventarios as $item)
                        <tr>
                            <td>{{ $item->id_inv }}</td>
                            <td>{{ $item->material->nom_mat ?? 'N/A' }}</td>
                            <td>{{ $item->proveedor->nom_prv ?? 'N/A' }}</td>
                            <td>{{ $item->cnt_inv }}</td>
                            <td>
                                <a href="{{ route('mantenimientos.inventario.index', ['edit' => $item->id_inv]) }}"
                                    class="btn-icon select">
                                    <ion-icon name="create-outline"></ion-icon>
                                </a>

                                <form action="{{ route('mantenimientos.inventario.destroy', $item->id_inv) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon delete" onclick="return confirm('¿Eliminar este registro?')">
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

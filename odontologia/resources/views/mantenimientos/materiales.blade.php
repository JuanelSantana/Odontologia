@extends('mantenimientos.mantenimientos')

@section('contenidomant')
    <div class="maintenance-view">
        <section class="form-container">
            <h2>{{ $materialEdit ? 'Editar Material' : 'Nuevo Material' }}</h2>

            <form
                action="{{ $materialEdit ? route('mantenimientos.materiales.update', $materialEdit->id_mat) : route('mantenimientos.materiales.store') }}"
                method="POST" class="modern-form">
                @csrf
                @if($materialEdit) @method('PUT') @endif

                <div class="form-grid">
                    <input type="text" name="nom_mat" placeholder="Nombre Material"
                        value="{{ $materialEdit->nom_mat ?? '' }}" required>
                    <input type="text" name="dsc_mat" placeholder="Descripción" value="{{ $materialEdit->dsc_mat ?? '' }}">
                    <input type="number" name="cnt_mat" placeholder="Cantidad" value="{{ $materialEdit->cnt_mat ?? '' }}">
                    <input type="number" step="0.01" name="cst_mat" placeholder="Costo"
                        value="{{ $materialEdit->cst_mat ?? '' }}">
                    <input type="text" name="tip_mat" placeholder="Tipo de Material"
                        value="{{ $materialEdit->tip_mat ?? '' }}">

                    <select name="id_prv">
                        <option value="">Seleccione Proveedor (Opcional)...</option>
                        @foreach($proveedores as $prv)
                            <option value="{{ $prv->id_prv }}" {{ ($materialEdit && $materialEdit->id_prv == $prv->id_prv) ? 'selected' : '' }}>
                                {{ $prv->nom_prv }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">{{ $materialEdit ? 'Guardar Cambios' : 'Insertar' }}</button>
                    @if($materialEdit)
                        <a href="{{ route('mantenimientos.materiales.index') }}" class="btn-secondary">Cancelar</a>
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
                        <th>Cantidad</th>
                        <th>Costo</th>
                        <th>Tipo</th>
                        <th>Proveedor</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($materiales as $item)
                        <tr>
                            <td>{{ $item->id_mat }}</td>
                            <td>{{ $item->nom_mat }}</td>
                            <td>{{ $item->dsc_mat }}</td>
                            <td>{{ $item->cnt_mat ?? '0' }}</td>
                            <td>${{ number_format($item->cst_mat, 2) }}</td>
                            <td>{{ $item->tip_mat }}</td>
                            <td>{{ $item->proveedor->nom_prv ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('mantenimientos.materiales.index', ['edit' => $item->id_mat]) }}"
                                    class="btn-icon select">
                                    <ion-icon name="create-outline"></ion-icon>
                                </a>

                                <form action="{{ route('mantenimientos.materiales.destroy', $item->id_mat) }}" method="POST"
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
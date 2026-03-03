@extends('mantenimientos.mantenimientos')

@section('contenidomant')
    <div class="maintenance-view">
        <section class="form-container">
            <h2>{{ $empleadoEdit ? 'Editar Empleado' : 'Nuevo Empleado' }}</h2>

            <form
                action="{{ $empleadoEdit ? route('mantenimientos.empleados.update', $empleadoEdit->id_emp) : route('mantenimientos.empleados.store') }}"
                method="POST" class="modern-form">
                @csrf
                @if($empleadoEdit) @method('PUT') @endif

                <div class="form-grid">
                    <input type="text" name="nom_emp" placeholder="Nombre" value="{{ $empleadoEdit->nom_emp ?? '' }}"
                        required>
                    <input type="text" name="ape_emp" placeholder="Apellidos" value="{{ $empleadoEdit->ape_emp ?? '' }}"
                        required>
                    <input type="text" name="dir_emp" placeholder="Dirección" value="{{ $empleadoEdit->dir_emp ?? '' }}">
                    <input type="text" name="tel_emp" placeholder="Teléfono" value="{{ $empleadoEdit->tel_emp ?? '' }}">
                    <input type="text" name="crg_emp" placeholder="Cargo" value="{{ $empleadoEdit->crg_emp ?? '' }}">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">{{ $empleadoEdit ? 'Guardar Cambios' : 'Insertar' }}</button>
                    @if($empleadoEdit)
                        <a href="{{ route('mantenimientos.empleados.index') }}" class="btn-secondary">Cancelar</a>
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
                        <th>Dirección</th>
                        <th>Teléfono</th>
                        <th>Cargo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($empleados as $item)
                        <tr>
                            <td>{{ $item->id_emp }}</td>
                            <td>{{ $item->nom_emp }} {{ $item->ape_emp }}</td>
                            <td>{{ $item->dir_emp }}</td>
                            <td>{{ $item->tel_emp }}</td>
                            <td>{{ $item->crg_emp }}</td>
                            <td>
                                <a href="{{ route('mantenimientos.empleados.index', ['edit' => $item->id_emp]) }}"
                                    class="btn-icon select">
                                    <ion-icon name="create-outline"></ion-icon>
                                </a>

                                <form action="{{ route('mantenimientos.empleados.destroy', $item->id_emp) }}" method="POST"
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
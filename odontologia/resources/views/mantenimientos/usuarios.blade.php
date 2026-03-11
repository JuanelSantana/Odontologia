@extends('mantenimientos.mantenimientos')

@section('contenidomant')
    <div class="maintenance-view">
        <section class="form-container">
            <h2>{{ $usuarioEdit ? 'Editar Usuario' : 'Nuevo Usuario' }}</h2>

            <form
                action="{{ $usuarioEdit ? route('mantenimientos.usuarios.update', $usuarioEdit->id) : route('mantenimientos.usuarios.store') }}"
                method="POST" class="modern-form">
                @csrf
                @if($usuarioEdit) @method('PUT') @endif

                <div class="form-grid">
                    <input type="text" name="name" placeholder="Nombre de Usuario"
                        value="{{ $usuarioEdit->name ?? '' }}" required>
                    
                    <input type="email" name="email" placeholder="Correo Electrónico"
                        value="{{ $usuarioEdit->email ?? '' }}" required>

                    <input type="password" name="password" placeholder="{{ $usuarioEdit ? 'Nueva Contraseña (opcional)' : 'Contraseña' }}" 
                        {{ $usuarioEdit ? '' : 'required' }}>

                    <select name="type" required>
                        <option value="">Seleccione Tipo...</option>
                        <option value="sysuser" {{ ($usuarioEdit && $usuarioEdit->type == 'sysuser') ? 'selected' : '' }}>Administrador</option>
                        <option value="doctor" {{ ($usuarioEdit && $usuarioEdit->type == 'doctor') ? 'selected' : '' }}>Doctor</option>
                        <option value="paciente" {{ ($usuarioEdit && $usuarioEdit->type == 'paciente') ? 'selected' : '' }}>Paciente</option>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">{{ $usuarioEdit ? 'Guardar Cambios' : 'Insertar' }}</button>
                    @if($usuarioEdit)
                        <a href="{{ route('mantenimientos.usuarios.index') }}" class="btn-secondary">Cancelar</a>
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
                        <th>Usuario</th>
                        <th>Email</th>
                        <th>Tipo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->email }}</td>
                            <td>
                                <span class="badge {{ $item->type }}">
                                    {{ ucfirst($item->type) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('mantenimientos.usuarios.index', ['edit' => $item->id]) }}"
                                    class="btn-icon select">
                                    <ion-icon name="create-outline"></ion-icon>
                                </a>

                                <form action="{{ route('mantenimientos.usuarios.destroy', $item->id) }}" method="POST"
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

    <style>
        .badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .badge.sysuser { background: #e0f2fe; color: #0369a1; }
        .badge.doctor { background: #fef3c7; color: #92400e; }
        .badge.paciente { background: #dcfce7; color: #15803d; }
    </style>
@endsection

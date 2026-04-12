@extends('procesos.procesos')

@section('contenidoproceso')
    <div class="maintenance-view">
        <section class="form-container">
            <h2>{{ $pagoEdit ? 'Editar Pago' : 'Registrar Nuevo Pago' }}</h2>

            <form
                action="{{ $pagoEdit ? route('procesos.pagos.update', $pagoEdit->id_pag) : route('procesos.pagos.store') }}"
                method="POST" class="modern-form">
                @csrf
                @if($pagoEdit) @method('PUT') @endif

                <div class="form-grid">
                    <div class="form-group" style="grid-column: span {{ $pagoEdit ? '3' : '2' }};">
                        <label>Cita / Paciente</label>
                        @if($pagoEdit)
                            <input type="text" value="Cita #{{ $pagoEdit->id_cit }} - {{ $pagoEdit->paciente->nom_pac }} ({{ date('d-m-Y', strtotime($pagoEdit->fec_pag)) }})" readonly disabled>
                            <input type="hidden" name="id_cit" value="{{ $pagoEdit->id_cit }}">
                        @else
                            <select name="id_cit" id="id_cit_select" required onchange="updateMontoRecomendado()">
                                <option value="">Seleccione Cita...</option>
                                @foreach($citas as $cita)
                                    @php
                                        $totalCita = $cita->servicios->sum('cst_srv');
                                    @endphp
                                    <option value="{{ $cita->id_cit }}" data-monto="{{ $totalCita }}">
                                        Cita #{{ $cita->id_cit }} - {{ $cita->paciente->nom_pac ?? 'N/A' }} ({{ date('d/m/Y', strtotime($cita->fec_cit)) }}) - Total Sugerido: ${{ number_format($totalCita, 2) }}
                                    </option>
                                @endforeach
                            </select>
                        @endif
                    </div>

                    <div class="form-group">
                        <label>Método de Pago</label>
                        <select name="id_mpa" required>
                            <option value="">Seleccione...</option>
                            @foreach($metodos as $metodo)
                                <option value="{{ $metodo->id_mpa }}" {{ ($pagoEdit && $pagoEdit->id_mpa == $metodo->id_mpa) ? 'selected' : '' }}>
                                    {{ $metodo->nom_mpa }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Monto a Pagar ($)</label>
                        <input type="number" step="0.01" name="mnt_pag" id="mnt_pag_input" placeholder="0.00"
                            value="{{ $pagoEdit->mnt_pag ?? '' }}" required min="0">
                    </div>

                    <div class="form-group">
                        <label>Fecha de Pago</label>
                        <input type="date" name="fec_pag" value="{{ $pagoEdit ? date('Y-m-d', strtotime($pagoEdit->fec_pag)) : date('Y-m-d') }}" required>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">{{ $pagoEdit ? 'Actualizar Pago' : 'Registrar Pago' }}</button>
                    @if($pagoEdit)
                        <a href="{{ route('procesos.pagos.index') }}" class="btn-secondary">Cancelar</a>
                    @endif
                </div>
            </form>
        </section>

        <hr>

        <section class="table-container">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>ID Pago</th>
                        <th>Fecha</th>
                        <th>Paciente</th>
                        <th>Monto</th>
                        <th>Método</th>
                        <th>Cita Ref.</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pagos as $item)
                        <tr>
                            <td>{{ $item->id_pag }}</td>
                            <td>{{ date('d/m/Y', strtotime($item->fec_pag)) }}</td>
                            <td>{{ $item->paciente->nom_pac ?? 'N/A' }} {{ $item->paciente->ape_pac ?? '' }}</td>
                            <td style="font-weight: bold; color: #1e3a8a;">${{ number_format($item->mnt_pag, 2) }}</td>
                            <td>{{ $item->metodoPago->nom_mpa ?? 'N/A' }}</td>
                            <td>#{{ $item->id_cit }}</td>
                            <td>
                                <a href="{{ route('procesos.pagos.index', ['edit' => $item->id_pag]) }}"
                                    class="btn-icon select">
                                    <ion-icon name="create-outline"></ion-icon>
                                </a>

                                <form action="{{ route('procesos.pagos.destroy', $item->id_pag) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon delete" onclick="return confirm('¿Eliminar este registro de pago?')">
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

    <script>
        function updateMontoRecomendado() {
            const select = document.getElementById('id_cit_select');
            const input = document.getElementById('mnt_pag_input');
            const selectedOption = select.options[select.selectedIndex];
            
            if (selectedOption.dataset.monto) {
                input.value = selectedOption.dataset.monto;
            }
        }
    </script>
@endsection

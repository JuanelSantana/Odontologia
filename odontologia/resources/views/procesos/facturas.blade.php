@extends('procesos.procesos')

@section('contenidoproceso')
    <div class="maintenance-view">
        <section class="form-container">
            <h2>{{ $facturaEdit ? 'Detalles de Factura' : 'Generar Nueva Factura' }}</h2>

            @if(!$facturaEdit)
                <form action="{{ route('procesos.facturas.store') }}" method="POST" class="modern-form">
                    @csrf
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Pago Asociado</label>
                            <select name="id_pag" id="id_pag_select" required onchange="populateServicios()">
                                <option value="">Seleccione Pago Pendiente...</option>
                                @foreach($pagos as $pago)
                                    @php
                                        $serviciosJson = $pago->cita->servicios->map(function ($s) {
                                            return ['id' => $s->id_srv, 'nom' => $s->nom_srv, 'precio' => $s->cst_srv];
                                        });
                                    @endphp
                                    <option value="{{ $pago->id_pag }}" data-servicios="{{ json_encode($serviciosJson) }}">
                                        Pago #{{ $pago->id_pag }} - {{ $pago->paciente->nom_pac ?? 'N/A' }}
                                        (${{ number_format($pago->mnt_pag, 2) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Número de Factura</label>
                            <input type="text" name="num_fac" value="{{ $nextNumFac }}" readonly
                                style="background: #f0f0f0; cursor: not-allowed;">
                        </div>

                        <div class="form-group">
                            <label>Fecha Emisión</label>
                            <input type="date" name="fec_emis_fac" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="form-group" style="grid-column: span 3;">
                            <label>Detalles de Servicios (Automático desde el Pago)</label>
                            <div id="servicios-container"
                                style="background: #fdfdfd; padding: 15px; border: 1px dashed #ccc; border-radius: 8px;">
                                <p id="placeholder-text" style="color: #999; font-style: italic;">Seleccione un pago para cargar
                                    los detalles...</p>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary">Generar Factura</button>
                        <button type="button" class="btn-secondary" onclick="addServicioRow()" style="margin-left: 10px;">+
                            Agregar Extra</button>
                    </div>
                </form>
            @else
                <div class="factura-details"
                    style="background: white; padding: 20px; border-radius: 8px; border: 1px solid #ddd;">
                    <h3>Factura #{{ $facturaEdit->num_fac }}</h3>
                    <p><strong>Fecha:</strong> {{ date('d/m/Y', strtotime($facturaEdit->fec_emis_fac)) }}</p>
                    <p><strong>Paciente:</strong> {{ $facturaEdit->pago->paciente->nom_pac ?? 'N/A' }}</p>
                    <hr>
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>Servicio</th>
                                <th>Cant</th>
                                <th>Precio</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($facturaEdit->detalles as $det)
                                <tr>
                                    <td>{{ $det->servicio->nom_srv ?? 'N/A' }}</td>
                                    <td>{{ $det->cant }}</td>
                                    <td>${{ number_format($det->precio, 2) }}</td>
                                    <td>${{ number_format($det->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div style="text-align: right; margin-top: 15px;">
                        <p><strong>Impuesto (18%):</strong> ${{ number_format($facturaEdit->imp_fac, 2) }}</p>
                        <p style="font-size: 1.2rem; color: #1e3a8a;"><strong>Total:</strong>
                            ${{ number_format($facturaEdit->ttl_fac, 2) }}</p>
                    </div>
                    <div style="display: flex; gap: 10px; margin-top: 20px;">
                        <a href="{{ route('procesos.facturas.index') }}" class="btn-secondary">Volver al listado</a>
                        <a href="{{ route('procesos.facturas.show', $facturaEdit->id_fac) }}" target="_blank"
                            class="btn-primary" style="text-decoration: none; display: flex; align-items: center; gap: 5px;">
                            <ion-icon name="print-outline"></ion-icon> Imprimir
                        </a>
                    </div>
                </div>
            @endif
        </section>

        <hr>

        <section class="table-container">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Num Factura</th>
                        <th>Fecha</th>
                        <th>Paciente</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($facturas as $item)
                        <tr>
                            <td>{{ $item->num_fac }}</td>
                            <td>{{ date('d/m/Y', strtotime($item->fec_emis_fac)) }}</td>
                            <td>{{ $item->pago->paciente->nom_pac ?? 'N/A' }} {{ $item->pago->paciente->ape_pac ?? '' }}</td>
                            <td>${{ number_format($item->ttl_fac, 2) }}</td>
                            <td>
                                <a href="{{ route('procesos.facturas.show', $item->id_fac) }}" target="_blank"
                                    class="btn-icon select" title="Imprimir / Ver Realista">
                                    <ion-icon name="print-outline"></ion-icon>
                                </a>

                                <a href="{{ route('procesos.facturas.index', ['edit' => $item->id_fac]) }}"
                                    class="btn-icon edit" title="Ver Detalles">
                                    <ion-icon name="eye-outline"></ion-icon>
                                </a>

                                <form action="{{ route('procesos.facturas.destroy', $item->id_fac) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon delete"
                                        onclick="return confirm('¿Eliminar esta factura?')">
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
        function populateServicios() {
            const select = document.getElementById('id_pag_select');
            const container = document.getElementById('servicios-container');
            const selectedOption = select.options[select.selectedIndex];

            container.innerHTML = ''; // Limpiar

            if (selectedOption.value === "") {
                container.innerHTML = '<p id="placeholder-text" style="color: #999; font-style: italic;">Seleccione un pago para cargar los detalles...</p>';
                return;
            }

            const servicios = JSON.parse(selectedOption.dataset.servicios);

            if (servicios.length === 0) {
                container.innerHTML = '<p style="color: #e74c3c;">Esta cita no tiene servicios registrados.</p>';
            }

            servicios.forEach(srv => {
                const row = document.createElement('div');
                row.className = 'servicio-row';
                row.style = 'display: flex; gap: 10px; margin-bottom: 10px; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 5px;';
                row.innerHTML = `
                        <div style="flex: 2;">
                            <input type="hidden" name="id_srv[]" value="${srv.id}">
                            <strong>${srv.nom}</strong>
                        </div>
                        <div style="flex: 1;">
                            <input type="number" name="cant[]" value="1" min="1" required style="width: 60px;"> Cant.
                        </div>
                        <div style="flex: 1; text-align: right;">
                            $${parseFloat(srv.precio).toLocaleString()}
                        </div>
                        <button type="button" class="btn-icon delete" onclick="this.parentElement.remove()" style="flex: 0.2;"><ion-icon name="close-outline"></ion-icon></button>
                    `;
                container.appendChild(row);
            });
        }

        function addServicioRow() {
            const container = document.getElementById('servicios-container');
            const placeholder = document.getElementById('placeholder-text');
            if (placeholder) placeholder.remove();

            const row = document.createElement('div');
            row.className = 'servicio-row';
            row.style = 'display: flex; gap: 10px; margin-bottom: 10px; align-items: center;';
            row.innerHTML = `
                    <select name="id_srv[]" style="flex: 2;" required>
                        <option value="">Seleccione Servicio...</option>
                        @foreach($servicios as $srv)
                            <option value="{{ $srv->id_srv }}">{{ $srv->nom_srv }} (${{ number_format($srv->cst_srv, 2) }})</option>
                        @endforeach
                    </select>
                    <input type="number" name="cant[]" placeholder="Cant" style="flex: 1; width: 60px;" value="1" min="1" required>
                    <button type="button" class="btn-icon delete" onclick="this.parentElement.remove()" style="flex: 0.2;"><ion-icon name="close-outline"></ion-icon></button>
                `;
            container.appendChild(row);
        }
    </script>
@endsection
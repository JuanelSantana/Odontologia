<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura {{ $factura->num_fac }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Courier+Prime:ital,wght@0,400;0,700;1,400;1,700&family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Courier Prime', 'Courier New', monospace;
            color: #000;
            line-height: 1.3;
            background-color: #f3f4f6;
            margin: 0;
            padding: 15px;
            display: flex;
            flex-direction: column;
            align-items: flex-start; /* Alinea todo a la izquierda en pantalla */
        }

        .no-print {
            margin-bottom: 15px;
            display: flex;
            gap: 8px;
            width: 48mm;
        }

        .btn {
            font-family: 'Inter', sans-serif;
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
            border: none;
            font-size: 11px;
            transition: 0.2s;
            text-align: center;
            flex: 1;
        }

        .btn-print { background: #4f46e5; color: white; }
        .btn-print:hover { background: #4338ca; }
        .btn-back { background: #e5e7eb; color: #374151; }
        .btn-back:hover { background: #d1d5db; }

        .invoice-card {
            background: #fff;
            width: 48mm; /* Ancho optimizado para papel de 5cm (50mm) */
            box-sizing: border-box;
            padding: 4mm 2mm;
            box-shadow: 0 4px 10px rgba(0,0,0,0.06);
            border: 1px solid #e5e7eb;
            position: relative;
            margin: 0; /* Alineado a la izquierda */
        }

        /* Efecto de papel rasgado para pantalla */
        .invoice-card::after {
            content: "";
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(-45deg, transparent 4px, #fff 0), linear-gradient(45deg, transparent 4px, #fff 0);
            background-size: 8px 8px;
            display: block;
        }

        .clinic-info {
            text-align: left;
            margin-bottom: 8px;
        }

        .clinic-info h1 {
            margin: 0 0 3px 0;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .clinic-info p {
            margin: 1px 0;
            font-size: 9px;
            color: #333;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }

        .invoice-meta {
            font-size: 9px;
            margin-bottom: 8px;
        }

        .invoice-meta h2 {
            margin: 0 0 2px 0;
            font-size: 11px;
            font-weight: 700;
        }

        .invoice-meta p {
            margin: 1px 0;
            font-weight: bold;
        }

        .client-info {
            font-size: 9px;
            margin-bottom: 8px;
        }

        .client-info h4 {
            margin: 0 0 2px 0;
            font-size: 9px;
            text-transform: uppercase;
            font-weight: 700;
        }

        .client-info p {
            margin: 1px 0;
        }

        .ticket-items {
            font-size: 9px;
            margin-bottom: 8px;
        }

        .ticket-item {
            margin-bottom: 6px;
        }

        .item-name {
            font-weight: bold;
            word-wrap: break-word;
        }

        .item-details {
            display: flex;
            justify-content: space-between;
            font-size: 9px;
            padding-left: 5px;
        }

        .totals {
            font-size: 9px;
            margin-top: 5px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 1px 0;
        }

        .total-row.grand-total {
            font-weight: bold;
            font-size: 11px;
            border-top: 1px dashed #000;
            margin-top: 4px;
            padding-top: 4px;
        }

        .paid-watermark {
            display: inline-block;
            border: 1px solid #000;
            color: #000;
            font-weight: bold;
            padding: 2px 6px;
            font-size: 10px;
            margin: 6px 0;
            text-transform: uppercase;
            transform: rotate(-3deg);
        }

        .footer {
            margin-top: 12px;
            text-align: center;
            font-size: 8px;
            color: #444;
            border-top: 1px dashed #000;
            padding-top: 8px;
        }



        @media print {
            @page {
                size: 50mm auto;
                margin: 0;
            }
            body {
                background: white;
                padding: 0;
                width: 50mm;
            }
            .no-print {
                display: none !important;
            }
            .invoice-card {
                box-shadow: none;
                border: none;
                width: 48mm;
                padding: 2mm;
                margin: 0;
            }
            .invoice-card::after {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <a href="{{ route('procesos.facturas.index') }}" class="btn btn-back">Volver</a>
        <button onclick="window.print()" class="btn btn-print">Imprimir</button>
    </div>

    <div class="invoice-card">
        <div class="clinic-info">
            <h1>Clínica Dental Dr. Cepín</h1>
            <p>Calle Principal #123, Santiago</p>
            <p>Tel: (809) 555-0123</p>
            <p>RNC: 1-01-23456-7</p>
        </div>

        <div class="divider"></div>

        <div class="invoice-meta">
            <h2>FACTURA</h2>
            <p>Num: {{ $factura->num_fac }}</p>
            <p>Fecha: {{ date('d/m/Y', strtotime($factura->fec_emis_fac)) }}</p>
        </div>

        <div class="divider"></div>

        <div class="client-info">
            <h4>PACIENTE:</h4>
            <p>{{ $factura->pago->paciente->nom_pac ?? 'N/A' }} {{ $factura->pago->paciente->ape_pac ?? '' }}</p>
            <p>Cédula: {{ $factura->pago->paciente->ced_pac ?? 'N/A' }}</p>
        </div>

        <div class="divider"></div>

        <div class="ticket-items">
            @php $subtotalTotal = 0; @endphp
            @foreach($factura->detalles as $det)
                @php $subtotalTotal += ($det->precio * $det->cant); @endphp
                <div class="ticket-item">
                    <div class="item-name">{{ $det->servicio->nom_srv ?? 'Servicio Especial' }}</div>
                    <div class="item-details">
                        <span>{{ $det->cant }} x ${{ number_format($det->precio, 2) }}</span>
                        <span class="item-subtotal">${{ number_format($det->subtotal, 2) }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="divider"></div>

        <div class="totals">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>${{ number_format($subtotalTotal, 2) }}</span>
            </div>
            <div class="total-row">
                <span>ITBIS (18%):</span>
                <span>${{ number_format($factura->imp_fac, 2) }}</span>
            </div>
            <div class="total-row grand-total">
                <span>TOTAL:</span>
                <span>${{ number_format($factura->ttl_fac, 2) }}</span>
            </div>
        </div>

        <div class="paid-watermark">PAGADO</div>

        <div class="footer">
            <p>¡Gracias por su visita!</p>
            <p>Dr. Cepin Clinic App</p>
        </div>


    </div>

</body>
</html>

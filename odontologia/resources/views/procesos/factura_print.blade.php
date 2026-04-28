<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura {{ $factura->num_fac }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            line-height: 1.6;
            background-color: #f4f7f6;
            margin: 0;
            padding: 20px;
        }

        .invoice-card {
            background: #fff;
            max-width: 800px;
            margin: 0 auto;
            padding: 40px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-radius: 8px;
            position: relative;
            overflow: hidden;
        }

        .invoice-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 8px;
            background: linear-gradient(90deg, #6b46c1, #4c51bf);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
        }

        .clinic-info h1 {
            margin: 0;
            color: #6b46c1;
            font-size: 24px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .clinic-info p {
            margin: 2px 0;
            font-size: 14px;
            color: #666;
        }

        .invoice-meta {
            text-align: right;
        }

        .invoice-meta h2 {
            margin: 0;
            font-size: 28px;
            color: #333;
        }

        .invoice-meta p {
            margin: 2px 0;
            font-weight: bold;
            color: #4c51bf;
        }

        .client-info {
            display: flex;
            justify-content: space-between;
            background: #f9fafb;
            padding: 20px;
            border-radius: 6px;
            margin-bottom: 30px;
        }

        .client-info div h4 {
            margin: 0 0 10px 0;
            color: #4c51bf;
            font-size: 12px;
            text-transform: uppercase;
        }

        .client-info div p {
            margin: 0;
            font-size: 15px;
            font-weight: 500;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .invoice-table th {
            text-align: left;
            padding: 12px;
            background: #f3f4f6;
            border-bottom: 2px solid #e5e7eb;
            color: #374151;
            font-size: 13px;
            text-transform: uppercase;
        }

        .invoice-table td {
            padding: 15px 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
        }

        .text-right { text-align: right; }

        .totals {
            margin-left: auto;
            width: 250px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
        }

        .total-row.grand-total {
            margin-top: 10px;
            padding-top: 15px;
            border-top: 2px solid #6b46c1;
            font-size: 20px;
            font-weight: bold;
            color: #6b46c1;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }

        .no-print {
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
            border: none;
            transition: 0.3s;
        }

        .btn-print { background: #6b46c1; color: white; }
        .btn-print:hover { background: #553c9a; }
        .btn-back { background: #e2e8f0; color: #4a5568; }
        .btn-back:hover { background: #cbd5e0; }

        .paid-watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 100px;
            color: rgba(5, 150, 105, 0.1);
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 10px;
            pointer-events: none;
            z-index: 0;
            border: 15px solid rgba(5, 150, 105, 0.1);
            padding: 20px;
            border-radius: 20px;
        }

        @media print {
            body { background: white; padding: 0; }
            .invoice-card { box-shadow: none; max-width: 100%; width: 100%; padding: 20px; }
            .no-print { display: none; }
            .invoice-card::before { height: 4px; }
            .paid-watermark { color: rgba(5, 150, 105, 0.05); border-color: rgba(5, 150, 105, 0.05); }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <a href="{{ route('procesos.facturas.index') }}" class="btn btn-back">Volver al listado</a>
        <button onclick="window.print()" class="btn btn-print">Imprimir Factura</button>
    </div>

    <div class="invoice-card">
        <div class="paid-watermark">PAGADO</div>
        <div class="header">
            <div class="clinic-info">
                <h1>Clínica Dental Dr. Cepín</h1>
                <p>Calle Principal #123, Santiago, RD</p>
                <p>Tel: (809) 555-0123</p>
                <p>RNC: 1-01-23456-7</p>
            </div>
            <div class="invoice-meta">
                <h2>FACTURA</h2>
                <p>{{ $factura->num_fac }}</p>
                <span>Fecha: {{ date('d/m/Y', strtotime($factura->fec_emis_fac)) }}</span>
            </div>
        </div>

        <div class="client-info">
            <div>
                <h4>CLIENTE / PACIENTE</h4>
                <p>{{ $factura->pago->paciente->nom_pac ?? 'N/A' }} {{ $factura->pago->paciente->ape_pac ?? '' }}</p>
                <span>Cédula: {{ $factura->pago->paciente->ced_pac ?? 'N/A' }}</span>
            </div>
            <div>
                <h4>ESTADO DE PAGO</h4>
                <p style="color: #059669;">PAGADO</p>
            </div>
        </div>

        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Descripción del Servicio</th>
                    <th class="text-right">Cant</th>
                    <th class="text-right">Precio Unit.</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @php $subtotalTotal = 0; @endphp
                @foreach($factura->detalles as $det)
                    @php $subtotalTotal += ($det->precio * $det->cant); @endphp
                    <tr>
                        <td>{{ $det->servicio->nom_srv ?? 'Servicio Especial' }}</td>
                        <td class="text-right">{{ $det->cant }}</td>
                        <td class="text-right">${{ number_format($det->precio, 2) }}</td>
                        <td class="text-right">${{ number_format($det->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

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

        <div class="footer">
            <p>Gracias por confiar en nuestra clínica para su salud dental.</p>
            <p>Esta es una factura electrónica generada por el sistema Dr. Cepin Clinic.</p>
        </div>
    </div>

</body>
</html>

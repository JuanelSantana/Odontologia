<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\DetalleFactura;
use App\Models\Pago;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacturaController extends Controller
{
    public function index(Request $request)
    {
        $facturas = Factura::with(['pago.paciente', 'detalles.servicio'])->orderBy('id_fac', 'desc')->get();
        $pagos = Pago::with(['paciente', 'cita.servicios'])->whereDoesntHave('factura')->get();
        $servicios = Servicio::all();

        $facturaEdit = null;
        if ($request->has('edit')) {
            $facturaEdit = Factura::with('detalles')->findOrFail($request->edit);
        }

        // Generar Siguiente Número de Factura Automático
        $ultimaFactura = Factura::orderBy('id_fac', 'desc')->first();
        $siguienteNumero = 1;
        if ($ultimaFactura) {
            // Extraer el número de una cadena tipo FAC-0001
            $numeroActual = (int) substr($ultimaFactura->num_fac, 4);
            $siguienteNumero = $numeroActual + 1;
        }
        $nextNumFac = 'FAC-' . str_pad($siguienteNumero, 4, '0', STR_PAD_LEFT);

        return view('procesos.facturas', compact('facturas', 'pagos', 'servicios', 'facturaEdit', 'nextNumFac'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pag' => 'required|exists:Pagos,id_pag',
            'num_fac' => 'required|string|unique:Facturas,num_fac',
            'fec_emis_fac' => 'required|date',
            'id_srv' => 'required|array',
            'cant' => 'required|array',
        ]);

        try {
            DB::beginTransaction();

            $total = 0;
            $detalles = [];

            foreach ($request->id_srv as $index => $srv_id) {
                $servicio = Servicio::find($srv_id);
                $cantidad = $request->cant[$index] ?? 1;
                $precio = $servicio->cst_srv;
                $subtotal = $precio * $cantidad;
                $total += $subtotal;

                $detalles[] = [
                    'id_srv' => $srv_id,
                    'cant' => $cantidad,
                    'precio' => $precio,
                    'subtotal' => $subtotal,
                ];
            }

            $factura = Factura::create([
                'id_pag' => $request->id_pag,
                'num_fac' => $request->num_fac,
                'fec_emis_fac' => $request->fec_emis_fac,
                'imp_fac' => $total * 0.18, // ITBIS 18%
                'ttl_fac' => $total + ($total * 0.18),
            ]);

            foreach ($detalles as $detalle) {
                $detalle['id_fac'] = $factura->id_fac;
                DetalleFactura::create($detalle);
            }

            DB::commit();
            return redirect()->route('procesos.facturas.index')->with('success', 'Factura generada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al generar factura: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $factura = Factura::findOrFail($id);
        $factura->delete(); // On delete cascade in DB for details
        return redirect()->route('procesos.facturas.index')->with('success', 'Factura eliminada correctamente.');
    }
}

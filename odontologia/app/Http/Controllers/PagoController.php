<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Cita;
use App\Models\Paciente;
use App\Models\MetodoPago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PagoController extends Controller
{
    public function index(Request $request)
    {
        $pagos = Pago::with(['paciente', 'cita', 'metodoPago'])->orderBy('id_pag', 'desc')->get();
        
        // Solo mostramos citas que no tengan el pago completo (simplificado: todas las citas para el dropdown)
        $citas = Cita::with(['paciente', 'servicios'])->orderBy('fec_cit', 'desc')->get();
        $metodos = MetodoPago::all();

        $pagoEdit = null;
        if ($request->has('edit')) {
            $pagoEdit = Pago::findOrFail($request->edit);
        }

        return view('procesos.pagos', compact('pagos', 'citas', 'metodos', 'pagoEdit'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_cit' => 'required|exists:Citas,id_cit',
            'id_mpa' => 'required|exists:Metodos_Pago,id_mpa',
            'mnt_pag' => 'required|numeric|min:0',
            'fec_pag' => 'required|date',
        ]);

        $cita = Cita::findOrFail($request->id_cit);

        Pago::create([
            'ced_pac' => $cita->ced_pac,
            'id_cit' => $request->id_cit,
            'id_mpa' => $request->id_mpa,
            'mnt_pag' => $request->mnt_pag,
            'fec_pag' => $request->fec_pag,
        ]);

        return redirect()->route('procesos.pagos.index')->with('success', 'Pago registrado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $pago = Pago::findOrFail($id);

        $request->validate([
            'id_mpa' => 'required|exists:Metodos_Pago,id_mpa',
            'mnt_pag' => 'required|numeric|min:0',
            'fec_pag' => 'required|date',
        ]);

        $pago->update($request->all());

        return redirect()->route('procesos.pagos.index')->with('success', 'Pago actualizado correctamente.');
    }

    public function destroy($id)
    {
        $pago = Pago::findOrFail($id);
        $pago->delete();

        return redirect()->route('procesos.pagos.index')->with('success', 'Pago eliminado correctamente.');
    }
}

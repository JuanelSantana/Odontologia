<?php

namespace App\Http\Controllers;

use App\Models\HistorialClinico;
use App\Models\Paciente;
use Illuminate\Http\Request;

class HistorialController extends Controller
{
    public function index(Request $request)
    {
        $historiales = HistorialClinico::with('paciente')->get();
        $pacientes = Paciente::all();

        $historialEdit = null;
        if ($request->has('edit')) {
            $historialEdit = HistorialClinico::findOrFail($request->edit);
        }

        return view('procesos.historial', compact('historiales', 'pacientes', 'historialEdit'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ced_pac' => 'required|exists:Pacientes,ced_pac',
            'dig_hcl' => 'required|string',
            'trt_prev_hcl' => 'nullable|string',
            'alg_hcl' => 'nullable|string',
            'mds_hcl' => 'nullable|string',
        ]);

        HistorialClinico::create($request->all());
        return redirect()->route('procesos.historial.index')->with('success', 'Historia clínica creada correctamente.');
    }

    public function update(Request $request, $id)
    {
        $historial = HistorialClinico::findOrFail($id);

        $request->validate([
            'ced_pac' => 'required|exists:Pacientes,ced_pac',
            'dig_hcl' => 'required|string',
            'trt_prev_hcl' => 'nullable|string',
            'alg_hcl' => 'nullable|string',
            'mds_hcl' => 'nullable|string',
        ]);

        $historial->update($request->all());
        return redirect()->route('procesos.historial.index')->with('success', 'Historia clínica actualizada correctamente.');
    }

    public function destroy($id)
    {
        $historial = HistorialClinico::findOrFail($id);
        $historial->delete();
        return redirect()->route('procesos.historial.index')->with('success', 'Historia clínica eliminada correctamente.');
    }
}

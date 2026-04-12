<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Paciente;
use App\Models\Doctor;
use App\Models\EstadoCita;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminCitaController extends Controller
{
    public function index(Request $request)
    {
        $citas = Cita::with(['paciente', 'doctor', 'estado'])->orderBy('fec_cit', 'desc')->get();
        $pacientes = Paciente::all();
        $doctores = Doctor::all();
        $estados = EstadoCita::all();
        $servicios = Servicio::all();

        $citaEdit = null;
        if ($request->has('edit')) {
            $citaEdit = Cita::with('servicios')->findOrFail($request->edit);
        }

        return view('procesos.citas', compact('citas', 'pacientes', 'doctores', 'estados', 'servicios', 'citaEdit'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ced_pac' => 'required|exists:Pacientes,ced_pac',
            'id_doc' => 'required|exists:Doctores,id_doc',
            'id_eci' => 'required|exists:Estado_Cita,id_eci',
            'fec_cit' => 'required|date',
            'mtv_cit' => 'nullable|string',
            'id_srv' => 'required|array',
        ]);

        try {
            DB::beginTransaction();
            $cita = Cita::create($request->all());
            $cita->servicios()->attach($request->id_srv);
            DB::commit();
            return redirect()->route('procesos.citas.index')->with('success', 'Cita creada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        $cita = Cita::findOrFail($id);
        $request->validate([
            'ced_pac' => 'required|exists:Pacientes,ced_pac',
            'id_doc' => 'required|exists:Doctores,id_doc',
            'id_eci' => 'required|exists:Estado_Cita,id_eci',
            'fec_cit' => 'required|date',
            'mtv_cit' => 'nullable|string',
            'id_srv' => 'required|array',
        ]);

        try {
            DB::beginTransaction();
            $cita->update($request->all());
            $cita->servicios()->sync($request->id_srv);
            DB::commit();
            return redirect()->route('procesos.citas.index')->with('success', 'Cita actualizada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $cita = Cita::findOrFail($id);
        $cita->delete();
        return redirect()->route('procesos.citas.index')->with('success', 'Cita eliminada correctamente.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Tratamiento;
use App\Models\Paciente;
use App\Models\Doctor;
use App\Models\TipoTratamiento;
use App\Models\Servicio;
use App\Models\Cita;
use Illuminate\Http\Request;

class TratamientoController extends Controller
{
    public function index(Request $request)
    {
        $tratamientos = Tratamiento::with(['paciente', 'doctor', 'tipo', 'servicio', 'cita'])->get();
        $pacientes = Paciente::all();
        $doctores = Doctor::all();
        $tipos = TipoTratamiento::all();
        $servicios = Servicio::all();
        $citas = Cita::all();

        $tratamientoEdit = null;
        if ($request->has('edit')) {
            $tratamientoEdit = Tratamiento::findOrFail($request->edit);
        }

        return view('mantenimientos.tratamientos', compact('tratamientos', 'pacientes', 'doctores', 'tipos', 'servicios', 'citas', 'tratamientoEdit'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ced_pac' => 'required|exists:Pacientes,ced_pac',
            'id_doc' => 'required|exists:Doctores,id_doc',
            'id_ttr' => 'required|exists:Tipos_Tratamiento,id_ttr',
            'id_srv' => 'required|exists:Servicios,id_srv',
            'dsc_tra' => 'nullable|string',
            'cst_tra' => 'required|numeric',
            'fec_ini_tra' => 'required|date',
            'fec_fin_tra' => 'nullable|date',
            'nom_tra' => 'nullable|string|max:100',
            'dur_tra' => 'nullable|string|max:50',
            'id_cit' => 'nullable|exists:Citas,id_cit',
        ]);

        Tratamiento::create($request->all());
        return redirect()->route('mantenimientos.tratamientos.index')->with('success', 'Tratamiento creado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $tratamiento = Tratamiento::findOrFail($id);

        $request->validate([
            'ced_pac' => 'required|exists:Pacientes,ced_pac',
            'id_doc' => 'required|exists:Doctores,id_doc',
            'id_ttr' => 'required|exists:Tipos_Tratamiento,id_ttr',
            'id_srv' => 'required|exists:Servicios,id_srv',
            'dsc_tra' => 'nullable|string',
            'cst_tra' => 'required|numeric',
            'fec_ini_tra' => 'required|date',
            'fec_fin_tra' => 'nullable|date',
            'nom_tra' => 'nullable|string|max:100',
            'dur_tra' => 'nullable|string|max:50',
            'id_cit' => 'nullable|exists:Citas,id_cit',
        ]);

        $tratamiento->update($request->all());
        return redirect()->route('mantenimientos.tratamientos.index')->with('success', 'Tratamiento actualizado correctamente.');
    }

    public function destroy($id)
    {
        Tratamiento::destroy($id);
        return redirect()->route('mantenimientos.tratamientos.index')->with('success', 'Tratamiento eliminado correctamente.');
    }
}

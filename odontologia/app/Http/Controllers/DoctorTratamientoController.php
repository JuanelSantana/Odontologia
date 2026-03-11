<?php

namespace App\Http\Controllers;

use App\Models\Tratamiento;
use App\Models\Paciente;
use App\Models\Doctor;
use App\Models\TipoTratamiento;
use App\Models\Servicio;
use App\Models\Cita;
use Illuminate\Http\Request;

class DoctorTratamientoController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $doctorActual = Doctor::where('user_id', $user->id)->firstOrFail();

        $tratamientos = Tratamiento::with(['paciente', 'tipo', 'servicio', 'cita'])
            ->where('id_doc', $doctorActual->id_doc)
            ->get();
            
        $pacientes = Paciente::all();
        $tipos = TipoTratamiento::all();
        $servicios = Servicio::all();
        
        // Show appointments only for this doctor
        $citas = Cita::where('id_doc', $doctorActual->id_doc)->get();

        $tratamientoEdit = null;
        if ($request->has('edit')) {
            $tratamientoEdit = Tratamiento::where('id_doc', $doctorActual->id_doc)
                ->findOrFail($request->edit);
        }

        return view('doctor.tratamientos', compact(
            'tratamientos', 'pacientes', 'tipos', 'servicios', 'citas', 'tratamientoEdit'
        ));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $doctorActual = Doctor::where('user_id', $user->id)->firstOrFail();

        $request->validate([
            'ced_pac' => 'required|exists:Pacientes,ced_pac',
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

        $data = $request->all();
        $data['id_doc'] = $doctorActual->id_doc;

        Tratamiento::create($data);
        return redirect()->route('doctor.tratamientos.index')->with('success', 'Tratamiento registrado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $doctorActual = Doctor::where('user_id', $user->id)->firstOrFail();

        $tratamiento = Tratamiento::where('id_doc', $doctorActual->id_doc)->findOrFail($id);

        $request->validate([
            'ced_pac' => 'required|exists:Pacientes,ced_pac',
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
        return redirect()->route('doctor.tratamientos.index')->with('success', 'Tratamiento actualizado correctamente.');
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $doctorActual = Doctor::where('user_id', $user->id)->firstOrFail();

        $tratamiento = Tratamiento::where('id_doc', $doctorActual->id_doc)->findOrFail($id);
        $tratamiento->delete();
        
        return redirect()->route('doctor.tratamientos.index')->with('success', 'Tratamiento eliminado correctamente.');
    }
}

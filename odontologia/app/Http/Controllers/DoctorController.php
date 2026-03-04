<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Especialidad;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $doctores = Doctor::with('especialidad')->get();
        $especialidades = Especialidad::all();

        $doctorEdit = null;
        if ($request->has('edit')) {
            $doctorEdit = Doctor::findOrFail($request->edit);
        }

        return view('mantenimientos.doctores', compact('doctores', 'especialidades', 'doctorEdit'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom_doc' => 'required|string|max:100',
            'ape_doc' => 'required|string|max:100',
            'ced_doc' => 'nullable|string|max:20',
            'tel_doc' => 'nullable|string|max:20',
            'eml_doc' => 'nullable|email|max:100',
            'id_esp' => 'required|exists:Especialidades,id_esp',
        ]);

        Doctor::create($request->all());
        return redirect()->route('mantenimientos.doctores.index')->with('success', 'Doctor creado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);

        $request->validate([
            'nom_doc' => 'required|string|max:100',
            'ape_doc' => 'required|string|max:100',
            'ced_doc' => 'nullable|string|max:20',
            'tel_doc' => 'nullable|string|max:20',
            'eml_doc' => 'nullable|email|max:100',
            'id_esp' => 'required|exists:Especialidades,id_esp',
        ]);

        $doctor->update($request->all());
        return redirect()->route('mantenimientos.doctores.index')->with('success', 'Doctor actualizado correctamente.');
    }

    public function destroy($id)
    {
        Doctor::destroy($id);
        return redirect()->route('mantenimientos.doctores.index')->with('success', 'Doctor eliminado correctamente.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Seguro;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    public function index(Request $request)
    {
        $pacientes = Paciente::with('seguro')->get();
        $seguros = Seguro::all();

        $pacienteEdit = null;
        if ($request->has('edit')) {
            $pacienteEdit = Paciente::findOrFail($request->edit);
        }

        return view('mantenimientos.pacientes', compact('pacientes', 'seguros', 'pacienteEdit'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom_pac' => 'required|string|max:100',
            'ape_pac' => 'required|string|max:100',
            'ced_pac' => 'required|string|max:20|unique:Pacientes,ced_pac',
            'gen_pac' => 'required|string|max:10',
            'fec_nac_pac' => 'required|date',
            'tel_pac' => 'required|string|max:20',
            'eml_pac' => 'required|email|max:100',
        ]);

        Paciente::create($request->all());
        return redirect()->route('mantenimientos.pacientes.index')->with('success', 'Paciente creado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $paciente = Paciente::findOrFail($id);

        $request->validate([
            'nom_pac' => 'required|string|max:100',
            'ape_pac' => 'required|string|max:100',
            'ced_pac' => 'required|string|max:20|unique:Pacientes,ced_pac,' . $id . ',id_pac',
            'gen_pac' => 'required|string|max:10',
            'fec_nac_pac' => 'required|date',
            'tel_pac' => 'required|string|max:20',
            'eml_pac' => 'required|email|max:100',
        ]);

        $paciente->update($request->all());
        return redirect()->route('mantenimientos.pacientes.index')->with('success', 'Paciente actualizado correctamente.');
    }

    public function destroy($id)
    {
        Paciente::destroy($id);
        return redirect()->route('mantenimientos.pacientes.index')->with('success', 'Paciente eliminado correctamente.');
    }
}

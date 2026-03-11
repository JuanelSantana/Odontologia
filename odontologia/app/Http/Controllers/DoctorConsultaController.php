<?php

namespace App\Http\Controllers;

use App\Models\ConsultaMedica;
use App\Models\Cita;
use App\Models\Paciente;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorConsultaController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $doctorActual = Doctor::where('user_id', $user->id)->firstOrFail();

        $consultas = ConsultaMedica::with(['paciente'])
            ->where('id_doc', $doctorActual->id_doc)
            ->orderBy('fec_con', 'desc')
            ->get();

        return view('doctor.consultas.index', compact('consultas'));
    }

    public function create(Request $request)
    {
        $user = auth()->user();
        $doctorActual = Doctor::where('user_id', $user->id)->firstOrFail();
        
        $pacientes = Paciente::all();
        $citaId = $request->get('cita_id');
        $pacienteCed = $request->get('paciente_ced');

        return view('doctor.consultas.create', compact('pacientes', 'citaId', 'pacienteCed', 'doctorActual'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $doctorActual = Doctor::where('user_id', $user->id)->firstOrFail();

        $request->validate([
            'ced_pac' => 'required|exists:Pacientes,ced_pac',
            'fec_con' => 'required|date',
            'motivo' => 'nullable|string',
            'observaciones' => 'nullable|string',
            'cita_id' => 'nullable|exists:Citas,id_cit'
        ]);

        $data = $request->all();
        $data['id_doc'] = $doctorActual->id_doc;

        ConsultaMedica::create($data);

        // Optional: Update the appointment status to Completed (e.g., id_eci = 2) if it came from an appointment
        if ($request->filled('cita_id')) {
            $cita = Cita::find($request->cita_id);
            if ($cita) {
                $cita->update(['id_eci' => 2]); // Assuming 2 is completed/attended
            }
        }

        return redirect()->route('doctor.consultas.index')->with('success', 'Consulta registrada correctamente.');
    }

    public function show(string $id)
    {
        $user = auth()->user();
        $doctorActual = Doctor::where('user_id', $user->id)->firstOrFail();

        $consulta = ConsultaMedica::with('paciente')
            ->where('id_doc', $doctorActual->id_doc)
            ->findOrFail($id);

        return view('doctor.consultas.show', compact('consulta'));
    }

    public function edit(string $id)
    {
        $user = auth()->user();
        $doctorActual = Doctor::where('user_id', $user->id)->firstOrFail();

        $consulta = ConsultaMedica::where('id_doc', $doctorActual->id_doc)->findOrFail($id);
        $pacientes = Paciente::all();

        return view('doctor.consultas.edit', compact('consulta', 'pacientes'));
    }

    public function update(Request $request, string $id)
    {
        $user = auth()->user();
        $doctorActual = Doctor::where('user_id', $user->id)->firstOrFail();

        $consulta = ConsultaMedica::where('id_doc', $doctorActual->id_doc)->findOrFail($id);

        $request->validate([
            'ced_pac' => 'required|exists:Pacientes,ced_pac',
            'fec_con' => 'required|date',
            'motivo' => 'nullable|string',
            'observaciones' => 'nullable|string',
        ]);

        $consulta->update($request->all());

        return redirect()->route('doctor.consultas.index')->with('success', 'Consulta actualizada correctamente.');
    }

    public function destroy(string $id)
    {
        $user = auth()->user();
        $doctorActual = Doctor::where('user_id', $user->id)->firstOrFail();

        $consulta = ConsultaMedica::where('id_doc', $doctorActual->id_doc)->findOrFail($id);
        $consulta->delete();

        return redirect()->route('doctor.consultas.index')->with('success', 'Consulta eliminada correctamente.');
    }
}

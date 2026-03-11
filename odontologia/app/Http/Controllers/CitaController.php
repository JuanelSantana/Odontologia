<?php
namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Paciente;
use App\Models\Doctor;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CitaController extends Controller
{
    public function index()
    {
        $citas = Cita::with(['paciente', 'doctor', 'estado'])->get();
        return view('citas.index', compact('citas'));
    }

    public function dashboard()
    {
        $patientCedula = Auth::user()->name; // La cédula está en el campo 'name' del User

        $paciente = Paciente::where('ced_pac', $patientCedula)->firstOrFail();

        // Próxima cita (la más cercana que no esté completada o cancelada)
        $proximaCita = Cita::where('ced_pac', $patientCedula)
            ->whereIn('id_eci', [1, 2, 6]) // Pendiente, Confirmada, En proceso
            ->where('fec_cit', '>=', now())
            ->orderBy('fec_cit', 'asc')
            ->with(['doctor', 'estado'])
            ->first();

        // Todas las citas (Historial unificado)
        $historialCitas = Cita::where('ced_pac', $patientCedula)
            ->orderBy('fec_cit', 'desc')
            ->with(['doctor', 'estado', 'servicios'])
            ->get();

        // Datos para el formulario de agendar
        $doctores = Doctor::all();
        $servicios = Servicio::all();

        // Historial Clínico
        $historialClinico = $paciente->historialClinico;

        return view('dashboard_paciente', compact('paciente', 'proximaCita', 'historialCitas', 'doctores', 'servicios', 'historialClinico'));
    }

    public function create()
    {
        return view('citas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_doc' => 'required|exists:Doctores,id_doc',
            'fec_cit' => 'required|date|after:now',
            'id_srv' => 'required|array',
            'id_srv.*' => 'exists:Servicios,id_srv',
            'mtv_cit' => 'nullable|string|max:255',
        ]);

        $timestamp = strtotime($request->fec_cit);
        $hora = (int) date('H', $timestamp);

        if ($hora < 8 || $hora >= 17) {
            return back()->withErrors(['fec_cit' => 'El horario de citas es únicamente de 8:00 AM a 5:00 PM.'])->withInput();
        }

        try {
            DB::beginTransaction();

            $cita = Cita::create([
                'ced_pac' => Auth::user()->name,
                'id_doc' => $request->id_doc,
                'id_eci' => 1, // Pendiente
                'fec_cit' => date('Y-m-d H:i:s', $timestamp),
                'mtv_cit' => $request->mtv_cit,
                'id_usr' => Auth::id(),
            ]);

            // Vincular servicios
            $cita->servicios()->attach($request->id_srv);

            DB::commit();

            return redirect()->route('paciente.dashboard')->with('success', 'Cita agendada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al agendar la cita: ' . $e->getMessage()])->withInput();
        }
    }

    public function show(Cita $cita)
    {
        return view('citas.show', compact('cita'));
    }

    public function edit(Cita $cita)
    {
        return view('citas.edit', compact('cita'));
    }

    public function update(Request $request, Cita $cita)
    {
        $validated = $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'fecha' => 'required|date',
            'hora' => 'required',
            'motivo' => 'nullable|string|max:255',
            'estado' => 'required|string'
        ]);

        $cita->update($validated);

        return redirect()->route('citas.index')->with('success', 'Cita actualizada correctamente.');
    }

    public function destroy(Cita $cita)
    {
        $cita->delete();

        return redirect()->route('citas.index')->with('success', 'Cita eliminada correctamente.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorCitaController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->type !== 'doctor') {
            abort(403, 'Acceso denegado');
        }

        $doctorActual = Doctor::where('user_id', $user->id)->firstOrFail();

        // Get appointments only for this doctor, order by date
        $citas = Cita::with(['paciente', 'estado', 'servicios'])
            ->where('id_doc', $doctorActual->id_doc)
            ->orderBy('fec_cit', 'desc')
            ->get();

        return view('doctor.citas', compact('citas'));
    }
}

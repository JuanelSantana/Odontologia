<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Paciente;
use App\Models\Doctor;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $hoy = Carbon::today();
        $finCalendario = Carbon::today()->addDays(20);
        $esDoctor = ($user->type === 'doctor');
        $id_doc = null;

        if ($esDoctor) {
            $doctorActual = Doctor::where('user_id', $user->id)->first();
            if ($doctorActual) {
                $id_doc = $doctorActual->id_doc;
            }
        }

        // KPIs
        $queryCitas = Cita::query();
        if ($esDoctor && $id_doc) {
            $queryCitas->where('id_doc', $id_doc);
        }

        $citasHoy = (clone $queryCitas)->whereDate('fec_cit', $hoy)->count();
        $citasPendientes = (clone $queryCitas)->where('id_eci', 1)->count(); // 1 = Pendiente
        
        $totalPacientes = 0;
        $proximaCita = null;

        if (!$esDoctor) {
            $totalPacientes = Paciente::count();
            $proximaCita = (clone $queryCitas)->where('fec_cit', '>=', Carbon::now())
                ->orderBy('fec_cit', 'asc')
                ->with(['paciente', 'doctor', 'estado'])
                ->first();
        }

        // Citas para el calendario (próximos 20 días)
        $citasCalendario = (clone $queryCitas)
            ->whereBetween('fec_cit', [$hoy->startOfDay()->toDateTimeString(), $finCalendario->endOfDay()->toDateTimeString()])
            ->orderBy('fec_cit', 'asc')
            ->with(['paciente', 'doctor', 'estado', 'servicios'])
            ->get();

        // Agrupar citas por fecha para facilitar el renderizado
        $citasPorDia = $citasCalendario->groupBy(function($cita) {
            return Carbon::parse($cita->fec_cit)->format('Y-m-d');
        });

        // Generar el rango de 20 días para el frontend
        $rangoDias = [];
        for ($i = 0; $i <= 20; $i++) {
            $dia = Carbon::today()->addDays($i);
            $rangoDias[] = [
                'fecha' => $dia->format('Y-m-d'),
                'label' => $dia->format('D d'),
                'nombre_dia' => $this->getSpanishDay($dia->format('l')),
                'numero_dia' => $dia->format('d'),
                'mes' => $this->getSpanishMonth($dia->format('F')),
                'es_hoy' => $dia->isToday(),
            ];
        }

        return view('dashboard', compact(
            'citasHoy', 
            'citasPendientes', 
            'totalPacientes', 
            'proximaCita', 
            'rangoDias', 
            'citasPorDia'
        ));
    }

    private function getSpanishDay($day)
    {
        $days = [
            'Monday' => 'Lun',
            'Tuesday' => 'Mar',
            'Wednesday' => 'Mié',
            'Thursday' => 'Jue',
            'Friday' => 'Vie',
            'Saturday' => 'Sáb',
            'Sunday' => 'Dom',
        ];
        return $days[$day] ?? $day;
    }

    private function getSpanishMonth($month)
    {
        $months = [
            'January' => 'Ene',
            'February' => 'Feb',
            'March' => 'Mar',
            'April' => 'Abr',
            'May' => 'May',
            'June' => 'Jun',
            'July' => 'Jul',
            'August' => 'Ago',
            'September' => 'Sep',
            'October' => 'Oct',
            'November' => 'Nov',
            'December' => 'Dic',
        ];
        return $months[$month] ?? $month;
    }
}

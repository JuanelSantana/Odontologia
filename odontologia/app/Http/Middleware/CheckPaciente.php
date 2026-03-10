<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPaciente
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $type = strtolower(Auth::user()->type);

            if ($type !== 'paciente') {
                // Si es un sysuser en una ruta de paciente, mandarlo a su dashboard
                if ($type === 'sysuser') {
                    return redirect()->route('dashboard')->withErrors([
                        'acceso' => 'No tienes permisos para acceder a esta sección.',
                    ]);
                }

                Auth::logout();
                return redirect()->route('iniciop')->withErrors([
                    'cedula' => 'No tienes permisos para acceder a esta sección.',
                ]);
            }
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckDoctor
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

            if ($type !== 'doctor') {
                // Si es un sysuser en una ruta de doctor, mandarlo a su dashboard
                if ($type === 'sysuser') {
                    return redirect()->route('dashboard')->withErrors([
                        'acceso' => 'No tienes permisos para acceder a esta sección.',
                    ]);
                }
                
                // Si es paciente
                if ($type === 'paciente') {
                     return redirect()->route('paciente.dashboard')->withErrors([
                        'acceso' => 'No tienes permisos para acceder a esta sección.',
                    ]);
                }

                Auth::logout();
                return redirect()->route('login')->withErrors([
                    'usuario' => 'No tienes permisos para acceder a esta sección.',
                ]);
            }
        }

        return $next($request);
    }
}

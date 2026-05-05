<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function registrar(Request $request)
    {
        // Validar los datos
        $request->validate([
            'usuario' => 'required|string|max:255|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'clave' => 'required|min:6',
        ]);

        // Crear el usuario 
        User::create([
            'name' => $request->usuario,
            'email' => $request->email,
            'password' => Hash::make($request->clave), // Encriptación
            'type' => 'sysuser',
        ]);

        return redirect()->route('login')->with('success', 'Registro exitoso. Por favor, inicia sesión.');
    }

    public function registrarPaciente(Request $request)
    {
        $request->validate([
            'cedula' => 'required|string|unique:Pacientes,ced_pac|unique:users,name',
            'password' => 'required|min:6',
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'genero' => 'required|string',
            'fecha_nacimiento' => 'required|date',
            'telefono' => 'required|string',
            'tipo' => 'required|string',
            'id_seguro' => 'nullable|integer',
        ]);

        try {
            DB::beginTransaction();

            // 1. Crear el usuario
            User::create([
                'name' => $request->cedula, // La cédula es el nombre de usuario
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'type' => 'paciente',
            ]);

            // 2. Crear el paciente
            Paciente::create([
                'ced_pac' => $request->cedula,
                'nom_pac' => $request->nombre,
                'ape_pac' => $request->apellido,
                'gen_pac' => $request->genero,
                'fec_nac_pac' => $request->fecha_nacimiento,
                'tel_pac' => $request->telefono,
                'eml_pac' => $request->email,
                'tip_pac' => $request->tipo,
                'cnd_sal_pac' => $request->condicion,
                'id_seg' => $request->id_seguro,
            ]);

            DB::commit();

            return redirect()->route('iniciop')->with('success', 'Registro exitoso. Por favor, inicia sesión.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Hubo un problema al registrar el paciente: ' . $e->getMessage()])->withInput();
        }
    }

    public function login(Request $request)
    {
        // Validar los datos
        $request->validate([
            'usuario' => 'required|string',
            'clave' => 'required|string',
        ]);


        if (Auth::attempt(['name' => $request->usuario, 'password' => $request->clave])) {
            $request->session()->regenerate();

            // Redirección inteligente según el tipo de usuario
            if (Auth::user()->type === 'sysuser') {
                return redirect()->route('dashboard');
            } elseif (Auth::user()->type === 'paciente') {
                return redirect()->route('paciente.dashboard');
            } elseif (Auth::user()->type === 'doctor') {
                return redirect()->route('doctor.dashboard');
            }

            Auth::logout();
            return back()->withErrors([
                'usuario' => 'Tipo de usuario no reconocido.',
            ])->onlyInput('usuario');
        }

        return back()->withErrors([
            'usuario' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('usuario');
    }

    public function loginPaciente(Request $request)
    {
        $request->validate([
            'cedula' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['name' => $request->cedula, 'password' => $request->password])) {
            $request->session()->regenerate();

            // Redirección inteligente según el tipo de usuario
            if (Auth::user()->type === 'paciente') {
                return redirect()->route('paciente.dashboard');
            } elseif (Auth::user()->type === 'sysuser') {
                return redirect()->route('dashboard');
            } elseif (Auth::user()->type === 'doctor') {
                return redirect()->route('doctor.dashboard');
            }

            Auth::logout();
            return back()->withErrors([
                'cedula' => 'Tipo de usuario no reconocido.',
            ])->onlyInput('cedula');
        }

        return back()->withErrors([
            'cedula' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('cedula');
    }

    public function logout(Request $request)
    {
        $redirectRoute = 'login';

        if (Auth::check() && strtolower(Auth::user()->type) === 'paciente') {
            $redirectRoute = 'iniciop';
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route($redirectRoute);
    }
}
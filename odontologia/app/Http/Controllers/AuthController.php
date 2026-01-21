<?php

namespace App\Http\Controllers;

use App\Models\User; // Importamos el modelo User de Laravel
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function registrar(Request $request)
    {
        // Validar los datos
        $request->validate([
            'usuario' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'clave' => 'required|min:6',
        ]);

        // Crear el usuario 
        User::create([
            'name' => $request->usuario,
            'email' => $request->email,
            'password' => Hash::make($request->clave), // Encriptación
        ]);

        return redirect()->route('login');
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
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'usuario' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('usuario');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $usuarios = User::all();
        $usuarioEdit = null;

        if ($request->has('edit')) {
            $usuarioEdit = User::findOrFail($request->edit);
        }

        return view('mantenimientos.usuarios', compact('usuarios', 'usuarioEdit'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'type' => 'required|string|in:sysuser,doctor,paciente',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => $request->type,
        ]);

        return redirect()->route('mantenimientos.usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:users,name,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
            'type' => 'required|string|in:sysuser,doctor,paciente',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'type' => $request->type,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $usuario->update($data);

        return redirect()->route('mantenimientos.usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy($id)
    {
        if (User::count() <= 1) {
            return back()->withErrors(['error' => 'No puedes eliminar el único usuario del sistema.']);
        }

        User::destroy($id);
        return redirect()->route('mantenimientos.usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }
}

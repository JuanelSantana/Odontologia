<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Especialidad;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $doctores = Doctor::with('especialidad')->get();
        $especialidades = Especialidad::all();

        $doctorEdit = null;
        if ($request->has('edit')) {
            $doctorEdit = Doctor::findOrFail($request->edit);
        }

        return view('mantenimientos.doctores', compact('doctores', 'especialidades', 'doctorEdit'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom_doc' => 'required|string|max:100',
            'ape_doc' => 'required|string|max:100',
            'ced_doc' => 'nullable|string|max:20|unique:Doctores,ced_doc',
            'tel_doc' => 'nullable|string|max:20',
            'eml_doc' => 'nullable|email|max:100|unique:users,email',
            'id_esp' => 'required|exists:Especialidades,id_esp',
            'password' => 'required|string|min:6', // Requerido para crear el usuario
        ]);

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            // 1. Crear el doctor
            $doctor = Doctor::create($request->except('password'));

            // 2. Crear el usuario asociado
            // Nombre de usuario: nombre + apellido en minúsculas, sin espacios
            $username = strtolower(str_replace(' ', '', $request->nom_doc . $request->ape_doc));
            
            // Asegurar que el username sea único
            $originalUsername = $username;
            $counter = 1;
            while (\App\Models\User::where('name', $username)->exists()) {
                $username = $originalUsername . $counter;
                $counter++;
            }

            $user = \App\Models\User::create([
                'name' => $username,
                'email' => $request->eml_doc ?: $username . '@clinic.com',
                'password' => \Illuminate\Support\Facades\Hash::make($request->password),
                'type' => 'doctor',
            ]);

            // 3. Vincular el usuario al doctor
            $doctor->update(['user_id' => $user->id]);

            \Illuminate\Support\Facades\DB::commit();

            return redirect()->route('mantenimientos.doctores.index')
                ->with('success', "Doctor creado correctamente. Usuario: $username");

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->withErrors(['error' => 'Error al crear el doctor y su usuario: ' . $e->getMessage()])->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);

        $request->validate([
            'nom_doc' => 'required|string|max:100',
            'ape_doc' => 'required|string|max:100',
            'ced_doc' => 'nullable|string|max:20|unique:Doctores,ced_doc,' . $id . ',id_doc',
            'tel_doc' => 'nullable|string|max:20',
            'eml_doc' => 'nullable|email|max:100|unique:users,email,' . ($doctor->user_id ?? 'NULL'),
            'id_esp' => 'required|exists:Especialidades,id_esp',
            'password' => 'nullable|string|min:6',
        ]);

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $doctor->update($request->except('password'));

            if ($request->filled('password') && $doctor->user_id) {
                $user = \App\Models\User::find($doctor->user_id);
                if ($user) {
                    $user->update([
                        'password' => \Illuminate\Support\Facades\Hash::make($request->password)
                    ]);
                }
            }

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('mantenimientos.doctores.index')->with('success', 'Doctor actualizado correctamente.');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->withErrors(['error' => 'Error al actualizar el doctor: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        Doctor::destroy($id);
        return redirect()->route('mantenimientos.doctores.index')->with('success', 'Doctor eliminado correctamente.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    public function index(Request $request)
    {
        $empleados = Empleado::all();

        $empleadoEdit = null;
        if ($request->has('edit')) {
            $empleadoEdit = Empleado::findOrFail($request->edit);
        }

        return view('mantenimientos.empleados', compact('empleados', 'empleadoEdit'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom_emp' => 'required|string|max:100',
            'ape_emp' => 'required|string|max:100',
            'dir_emp' => 'nullable|string|max:200',
            'tel_emp' => 'nullable|string|max:20',
            'crg_emp' => 'nullable|string|max:50',
        ]);

        Empleado::create($request->all());
        return redirect()->route('mantenimientos.empleados.index')->with('success', 'Empleado creado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $empleado = Empleado::findOrFail($id);

        $request->validate([
            'nom_emp' => 'required|string|max:100',
            'ape_emp' => 'required|string|max:100',
            'dir_emp' => 'nullable|string|max:200',
            'tel_emp' => 'nullable|string|max:20',
            'crg_emp' => 'nullable|string|max:50',
        ]);

        $empleado->update($request->all());
        return redirect()->route('mantenimientos.empleados.index')->with('success', 'Empleado actualizado correctamente.');
    }

    public function destroy($id)
    {
        Empleado::destroy($id);
        return redirect()->route('mantenimientos.empleados.index')->with('success', 'Empleado eliminado correctamente.');
    }
}

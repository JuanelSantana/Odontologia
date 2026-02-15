<?php

namespace App\Http\Controllers;

use App\Models\Especialidad;
use Illuminate\Http\Request;

class EspecialidadController extends Controller
{
    public function store(Request $request)
    {
        Especialidad::create($request->all());
        return redirect()->route('mantenimientos.especialidades.index');
    }

    public function index(Request $request)
    {
        $especialidades = Especialidad::all();

        $especialidadEdit = null;
        if ($request->has('edit')) {
            $especialidadEdit = Especialidad::findOrFail($request->edit);
        }

        return view('mantenimientos.especialidades', compact('especialidades', 'especialidadEdit'));
    }

    public function update(Request $request, $id)
    {
        $item = Especialidad::findOrFail($id); // SELECT * WHERE id_esp = $id
        $item->update($request->all());        // UPDATE ...
        return redirect()->route('mantenimientos.especialidades.index');
    }

    public function destroy($id)
    {
        Especialidad::destroy($id); // DELETE FROM Especialidades WHERE id_esp = $id
        return redirect()->route('mantenimientos.especialidades.index');
    }
}
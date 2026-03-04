<?php

namespace App\Http\Controllers;

use App\Models\Seguro;
use Illuminate\Http\Request;

class SeguroController extends Controller
{
    public function index(Request $request)
    {
        $seguros = Seguro::all();

        $seguroEdit = null;
        if ($request->has('edit')) {
            $seguroEdit = Seguro::findOrFail($request->edit);
        }

        return view('mantenimientos.seguros', compact('seguros', 'seguroEdit'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom_seg' => 'required|string|max:100',
            'tel_seg' => 'nullable|string|max:20',
        ]);

        Seguro::create($request->all());
        return redirect()->route('mantenimientos.seguros.index')->with('success', 'Seguro creado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $seguro = Seguro::findOrFail($id);

        $request->validate([
            'nom_seg' => 'required|string|max:100',
            'tel_seg' => 'nullable|string|max:20',
        ]);

        $seguro->update($request->all());
        return redirect()->route('mantenimientos.seguros.index')->with('success', 'Seguro actualizado correctamente.');
    }

    public function destroy($id)
    {
        Seguro::destroy($id);
        return redirect()->route('mantenimientos.seguros.index')->with('success', 'Seguro eliminado correctamente.');
    }
}

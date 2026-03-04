<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $materiales = Material::with('proveedor')->get();
        $proveedores = Proveedor::all();

        $materialEdit = null;
        if ($request->has('edit')) {
            $materialEdit = Material::findOrFail($request->edit);
        }

        return view('mantenimientos.materiales', compact('materiales', 'proveedores', 'materialEdit'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom_mat' => 'required|string|max:100',
            'dsc_mat' => 'nullable|string',
            'cnt_mat' => 'nullable|integer',
            'cst_mat' => 'nullable|numeric',
            'tip_mat' => 'nullable|string|max:50',
            'id_prv' => 'nullable|exists:Proveedores,id_prv',
        ]);

        Material::create($request->all());
        return redirect()->route('mantenimientos.materiales.index')->with('success', 'Material creado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $material = Material::findOrFail($id);

        $request->validate([
            'nom_mat' => 'required|string|max:100',
            'dsc_mat' => 'nullable|string',
            'cnt_mat' => 'nullable|integer',
            'cst_mat' => 'nullable|numeric',
            'tip_mat' => 'nullable|string|max:50',
            'id_prv' => 'nullable|exists:Proveedores,id_prv',
        ]);

        $material->update($request->all());
        return redirect()->route('mantenimientos.materiales.index')->with('success', 'Material actualizado correctamente.');
    }

    public function destroy($id)
    {
        Material::destroy($id);
        return redirect()->route('mantenimientos.materiales.index')->with('success', 'Material eliminado correctamente.');
    }
}

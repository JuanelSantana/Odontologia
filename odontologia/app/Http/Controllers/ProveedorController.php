<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index(Request $request)
    {
        $proveedores = Proveedor::all();

        $proveedorEdit = null;
        if ($request->has('edit')) {
            $proveedorEdit = Proveedor::findOrFail($request->edit);
        }

        return view('mantenimientos.proveedores', compact('proveedores', 'proveedorEdit'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom_prv' => 'required|string|max:100',
            'loc_prv' => 'nullable|string|max:100',
            'tel_prv' => 'nullable|string|max:20',
        ]);

        Proveedor::create($request->all());
        return redirect()->route('mantenimientos.proveedores.index')->with('success', 'Proveedor creado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $proveedor = Proveedor::findOrFail($id);

        $request->validate([
            'nom_prv' => 'required|string|max:100',
            'loc_prv' => 'nullable|string|max:100',
            'tel_prv' => 'nullable|string|max:20',
        ]);

        $proveedor->update($request->all());
        return redirect()->route('mantenimientos.proveedores.index')->with('success', 'Proveedor actualizado correctamente.');
    }

    public function destroy($id)
    {
        Proveedor::destroy($id);
        return redirect()->route('mantenimientos.proveedores.index')->with('success', 'Proveedor eliminado correctamente.');
    }
}

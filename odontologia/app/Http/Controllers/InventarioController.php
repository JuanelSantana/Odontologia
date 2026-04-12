<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Models\Material;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    public function index(Request $request)
    {
        $inventarios = Inventario::with(['material', 'proveedor'])->get();
        $materiales = Material::all();
        $proveedores = Proveedor::all();

        $inventarioEdit = null;
        if ($request->has('edit')) {
            $inventarioEdit = Inventario::findOrFail($request->edit);
        }

        return view('mantenimientos.inventario', compact('inventarios', 'materiales', 'proveedores', 'inventarioEdit'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_mat' => 'required|exists:Materiales,id_mat',
            'id_prv' => 'required|exists:Proveedores,id_prv',
            'cnt_inv' => 'required|integer|min:0',
        ]);

        Inventario::create($request->all());
        return redirect()->route('mantenimientos.inventario.index')->with('success', 'Registro de inventario creado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $inventario = Inventario::findOrFail($id);

        $request->validate([
            'id_mat' => 'required|exists:Materiales,id_mat',
            'id_prv' => 'required|exists:Proveedores,id_prv',
            'cnt_inv' => 'required|integer|min:0',
        ]);

        $inventario->update($request->all());
        return redirect()->route('mantenimientos.inventario.index')->with('success', 'Registro de inventario actualizado correctamente.');
    }

    public function destroy($id)
    {
        $inventario = Inventario::findOrFail($id);
        $inventario->delete();
        return redirect()->route('mantenimientos.inventario.index')->with('success', 'Registro de inventario eliminado correctamente.');
    }
}

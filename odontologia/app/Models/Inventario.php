<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected $table = 'Inventario';
    protected $primaryKey = 'id_inv';
    public $timestamps = false;

    public function material()
    {
        return $this->belongsTo(Material::class, 'id_mat');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'id_prv');
    }
}

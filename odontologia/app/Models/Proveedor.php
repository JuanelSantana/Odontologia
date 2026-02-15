<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'Proveedores';
    protected $primaryKey = 'id_prv';
    public $timestamps = false;

    public function materiales()
    {
        return $this->hasMany(Material::class, 'id_prv');
    }

    public function inventarios()
    {
        return $this->hasMany(Inventario::class, 'id_prv');
    }
}

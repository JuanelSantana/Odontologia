<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $table = 'Materiales';
    protected $primaryKey = 'id_mat';
    public $timestamps = false;

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'id_prv');
    }

    public function inventarios()
    {
        return $this->hasMany(Inventario::class, 'id_mat');
    }

    public function tratamientos()
    {
        return $this->belongsToMany(Tratamiento::class, 'Tratamientos_Materiales', 'id_mat', 'id_tra')
            ->withPivot('cant_usada');
    }

    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'Servicios_Materiales', 'id_mat', 'id_srv');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $table = 'Servicios';
    protected $primaryKey = 'id_srv';
    public $timestamps = false;

    // Relación Muchos a Muchos con Citas
    public function citas()
    {
        return $this->belongsToMany(Cita::class, 'Citas_Servicios', 'id_srv', 'id_cit');
    }

    // Relación Muchos a Muchos con Materiales
    public function materiales()
    {
        return $this->belongsToMany(Material::class, 'Servicios_Materiales', 'id_srv', 'id_mat');
    }
}

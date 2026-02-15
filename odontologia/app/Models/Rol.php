<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'Roles';
    protected $primaryKey = 'id_rol';
    public $timestamps = false;

    public function usuarios()
    {
        return $this->hasMany(User::class, 'id_rol');
    }

    public function permisos()
    {
        return $this->belongsToMany(Permiso::class, 'Roles_Permisos', 'id_rol', 'id_prm');
    }
}

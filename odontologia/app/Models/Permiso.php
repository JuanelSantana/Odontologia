<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    protected $table = 'Permisos';
    protected $primaryKey = 'id_prm';
    public $timestamps = false;

    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'Roles_Permisos', 'id_prm', 'id_rol');
    }
}

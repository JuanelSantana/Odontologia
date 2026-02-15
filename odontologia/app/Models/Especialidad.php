<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Especialidad extends Model
{
    protected $table = 'Especialidades'; // Nombre exacto en SQL
    protected $primaryKey = 'id_esp'; // Llave primaria personalizada
    public $timestamps = false;
}

?>
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seguro extends Model
{
    protected $table = 'Seguros'; // Nombre exacto en SQL
    protected $primaryKey = 'id_seg'; // Llave primaria personalizada
    public $timestamps = false; // Tu SQL no tiene created_at/updated_at
}

?>
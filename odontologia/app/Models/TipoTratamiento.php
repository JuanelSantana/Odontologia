<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoTratamiento extends Model
{
    protected $table = 'Tipos_Tratamiento';
    protected $primaryKey = 'id_ttr';
    public $timestamps = false;

    public function tratamientos()
    {
        return $this->hasMany(Tratamiento::class, 'id_ttr');
    }
}

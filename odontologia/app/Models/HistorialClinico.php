<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialClinico extends Model
{
    protected $table = 'Historial_Clinico';
    protected $primaryKey = 'id_hcl';
    public $timestamps = false;

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'id_pac');
    }
}

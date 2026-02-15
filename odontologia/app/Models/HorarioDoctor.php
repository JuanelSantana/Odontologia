<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HorarioDoctor extends Model
{
    protected $table = 'Horarios_Doctores';
    protected $primaryKey = 'id_hdo';
    public $timestamps = false;

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'id_doc');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoCita extends Model
{
    protected $table = 'Estado_Cita';
    protected $primaryKey = 'id_eci';
    public $timestamps = false;

    public function citas()
    {
        return $this->hasMany(Cita::class, 'id_eci');
    }
}

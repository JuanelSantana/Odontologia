<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetodoPago extends Model
{
    protected $table = 'Metodos_Pago';
    protected $primaryKey = 'id_mpa';
    public $timestamps = false;

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'id_mpa');
    }
}

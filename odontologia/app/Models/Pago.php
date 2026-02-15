<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'Pagos';
    protected $primaryKey = 'id_pag';
    public $timestamps = false;

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'id_pac');
    }

    public function cita()
    {
        return $this->belongsTo(Cita::class, 'id_cit');
    }

    public function metodoPago()
    {
        return $this->belongsTo(MetodoPago::class, 'id_mpa');
    }

    public function factura()
    {
        return $this->hasOne(Factura::class, 'id_pag');
    }
}

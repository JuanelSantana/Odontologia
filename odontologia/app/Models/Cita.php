<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $table = 'Citas';
    protected $primaryKey = 'id_cit';
    public $timestamps = false;

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'id_pac');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'id_doc');
    }

    public function estado()
    {
        return $this->belongsTo(EstadoCita::class, 'id_eci');
    }

    // Relación Muchos a Muchos con Servicios
    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'Citas_Servicios', 'id_cit', 'id_srv');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usr');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'id_cit');
    }

    public function tratamientos()
    {
        return $this->hasMany(Tratamiento::class, 'id_cit');
    }
}

?>
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tratamiento extends Model
{
    protected $table = 'Tratamientos';
    protected $primaryKey = 'id_tra';
    public $timestamps = false;

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'id_pac');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'id_doc');
    }

    public function tipo()
    {
        return $this->belongsTo(TipoTratamiento::class, 'id_ttr');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'id_srv');
    }

    public function cita()
    {
        return $this->belongsTo(Cita::class, 'id_cit');
    }

    public function materiales()
    {
        return $this->belongsToMany(Material::class, 'Tratamientos_Materiales', 'id_tra', 'id_mat')
            ->withPivot('cant_usada');
    }

    public function doctores()
    {
        return $this->belongsToMany(Doctor::class, 'Tratamientos_Doctores', 'id_tra', 'id_doc');
    }
}

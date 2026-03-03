<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $table = 'Doctores';
    protected $primaryKey = 'id_doc';
    public $timestamps = false;

    protected $fillable = [
        'nom_doc',
        'ape_doc',
        'ced_doc',
        'tel_doc',
        'eml_doc',
        'id_esp'
    ];


    public function especialidad()
    {
        return $this->belongsTo(Especialidad::class, 'id_esp');
    }

    public function horarios()
    {
        return $this->hasMany(HorarioDoctor::class, 'id_doc');
    }

    public function citas()
    {
        return $this->hasMany(Cita::class, 'id_doc');
    }

    public function tratamientos()
    {
        return $this->hasMany(Tratamiento::class, 'id_doc');
    }

    public function tratamientos_participados()
    {
        return $this->belongsToMany(Tratamiento::class, 'Tratamientos_Doctores', 'id_doc', 'id_tra');
    }
}

?>
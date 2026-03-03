<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tratamiento extends Model
{
    protected $table = 'Tratamientos';
    protected $primaryKey = 'id_tra';
    public $timestamps = false;

    protected $fillable = [
        'ced_pac',
        'id_doc',
        'id_ttr',
        'id_srv',
        'dsc_tra',
        'cst_tra',
        'fec_ini_tra',
        'fec_fin_tra',
        'nom_tra',
        'dur_tra',
        'id_cit'
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'ced_pac', 'ced_pac');
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

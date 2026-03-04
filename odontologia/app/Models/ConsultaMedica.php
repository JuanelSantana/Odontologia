<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultaMedica extends Model
{
    protected $table = 'Consultas_Medicas';
    protected $primaryKey = 'id_con';
    public $timestamps = false;

    protected $fillable = [
        'ced_pac',
        'id_doc',
        'fec_con',
        'motivo',
        'observaciones'
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'ced_pac', 'ced_pac');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'id_doc');
    }
}

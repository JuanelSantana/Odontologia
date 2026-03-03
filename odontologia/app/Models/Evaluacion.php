<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    protected $table = 'Evaluaciones';
    protected $primaryKey = 'id_eval';
    public $timestamps = false;

    protected $fillable = [
        'ced_pac',
        'id_doc',
        'fecha_eval',
        'resultado'
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

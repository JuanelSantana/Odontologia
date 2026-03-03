<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diagnostico extends Model
{
    protected $table = 'Diagnosticos';
    protected $primaryKey = 'id_dia';
    public $timestamps = false;

    protected $fillable = [
        'ced_pac',
        'id_doc',
        'fecha_dia',
        'descripcion'
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

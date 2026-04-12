<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialClinico extends Model
{
    protected $table = 'Historial_Clinico';
    protected $primaryKey = 'id_hcl';
    public $timestamps = false;

    protected $fillable = [
        'ced_pac',
        'dig_hcl',
        'trt_prev_hcl',
        'alg_hcl',
        'mds_hcl'
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'ced_pac', 'ced_pac');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    protected $table = 'Cotizaciones';
    protected $primaryKey = 'id_coti';
    public $timestamps = false;

    protected $fillable = [
        'ced_pac',
        'fecha_coti',
        'monto',
        'detalle'
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'ced_pac', 'ced_pac');
    }
}

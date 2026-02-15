<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $table = 'Pacientes'; // Nombre exacto en SQL
    protected $primaryKey = 'id_pac'; // Llave primaria personalizada
    public $timestamps = false; // Tu SQL no tiene created_at/updated_at

    protected $fillable = [
        'nom_pac',
        'ape_pac',
        'ced_pac',
        'gen_pac',
        'fec_nac_pac',
        'tel_pac',
        'eml_pac',
        'tip_pac',
        'cnd_sal_pac',
        'id_seg',
    ];

    // Relación: Un paciente pertenece a un seguro
    public function seguro()
    {
        return $this->belongsTo(Seguro::class, 'id_seg');
    }

    // Relación: Un paciente tiene muchas citas
    public function citas()
    {
        return $this->hasMany(Cita::class, 'id_pac');
    }

    public function historialClinico()
    {
        return $this->hasOne(HistorialClinico::class, 'id_pac');
    }

    public function tratamientos()
    {
        return $this->hasMany(Tratamiento::class, 'id_pac');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'id_pac');
    }
}

?>
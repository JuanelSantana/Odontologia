<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $table = 'Facturas';
    protected $primaryKey = 'id_fac';
    public $timestamps = false;

    public function pago()
    {
        return $this->belongsTo(Pago::class, 'id_pag');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleFactura::class, 'id_fac');
    }
}

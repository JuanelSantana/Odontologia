<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleFactura extends Model
{
    protected $table = 'Detalle_Factura';
    protected $primaryKey = 'id_det';
    public $timestamps = false;

    protected $fillable = [
        'id_fac',
        'id_srv',
        'cant',
        'precio',
        'subtotal'
    ];

    public function factura()
    {
        return $this->belongsTo(Factura::class, 'id_fac');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'id_srv');
    }
}

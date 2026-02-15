<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = 'Empleados';
    protected $primaryKey = 'id_emp';
    public $timestamps = false;
}

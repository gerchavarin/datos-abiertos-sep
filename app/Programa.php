<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Programa extends Model
{
    protected $fillable = [
        'nombre',
        'llave',
        'acuerdo',
        'fecha_otorgamiento_rvoe',
        'fecha_retiro_rvoe',
        'plantel_id',
        'nivel_id',
        'area_id',
        'modalidad_id',
        'estatus_id',
        'motivo_retiro_id',
    ];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Institucion extends Model
{
    protected $fillable = [
        'nombre',
        'razon_social',
        'autorizada_equivalencias',
        'fecha_aut_revalidacion_equivalencia',
        'fecha_rev_revalidacion_equivalencia',
        'grupo_id',
        'tipo_rvoe_id',
    ];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plantel extends Model
{
    protected $fillable = [
        'nombre',
        'excelencia',
        'correo_electronico',
        'pagina_web',
        'institucion_id',
        'direccion_id',
        'director_id',
        'telefono_id',
    ];
}

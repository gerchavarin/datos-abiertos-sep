<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'indice_superior',
        'indice_inferior',
        'catalogo_id',
    ];
}

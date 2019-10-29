<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoRvoe extends Model
{
    protected $fillable = [
        'nombre',
        'indice_superior',
        'indice_inferior',
        'catalogo_id',
    ];
}

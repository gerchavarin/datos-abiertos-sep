<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Direccion extends Model
{
    protected $fillable = [
        'calle',
        'codigo_postal',
        'ampliacion',
        'municipio_id',
    ];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusInstitucion extends Model
{
    protected $fillable = [
        'institucion_id',
        'status_id',
        'sostenimiento_id',
        'nombre_institucion',
        'entidad_id',
        'descripcion_entidad',
        'descripcion_status'
    ];
}
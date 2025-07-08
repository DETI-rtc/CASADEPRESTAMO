<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Envioresumen extends Model
{
    use HasFactory;
    protected $fillable = [
        'fecha_envio',
        'fecha_referencia',
        'correlativo',
        'resumen',
        'baja',
        'nombrexml',
        'mensaje_sunat',
        'codigo_sunat',
        'ticket',
        'estado',

        
    ];
}

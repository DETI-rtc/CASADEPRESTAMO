<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipoafectacion extends Model
{
    use HasFactory;
    protected $table='tipo_afectacion';
    protected $keyType = 'string';
    protected $fillable = [
        'descripcion',
        'letra',
        'codigo',
        'nombre',
        'tipo',
        'estado',
        
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calificacion extends Model
{
    use HasFactory;
    protected $table='pre_calificacion_analista';
   
    protected $fillable = [
        'idanalista',
        'promedio',
        'mes',
    ];
}

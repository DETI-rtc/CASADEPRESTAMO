<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleCalificacion extends Model
{
    use HasFactory;
    protected $table='pre_det_calificacion';
   
    protected $fillable = [
        'idcalificacion',
        'idcliente',
        'puntaje',
        'fecha',
        'idcredito',
    ];
    
}

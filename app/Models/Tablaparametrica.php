<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tablaparametrica extends Model
{
    use HasFactory;
    protected $table='tabla_parametrica';
    protected $keyType = 'string';
    protected $fillable = [
        'tipo',
        'codigo',
        'descripcion',
        
        
    ];
}

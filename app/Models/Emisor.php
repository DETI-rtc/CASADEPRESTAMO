<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emisor extends Model
{
    use HasFactory;
    protected $table='emisor';
    protected $fillable = [
        'tipodoc',
        'ruc',
        'razon_social',
        'nombre_comercial',
        'direccion',
        'pais',
        'departamento',
        'provincia',
        'distrito',
        'ubigeo',
        'usurio_sol',
        'clave_sol'
        
    ];
}

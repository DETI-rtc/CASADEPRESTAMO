<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipocomprobante extends Model
{
    use HasFactory;
    protected $table='tipo_comprobante';
    protected $keyType = 'string';
    protected $fillable = [
        
        'descripcion',
        'tipo_serie',
        'estado',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tiponotadebito extends Model
{
    use HasFactory;
    protected $table='tipo_notadebito';
    protected $keyType = 'string';
    protected $fillable = [
        
        'descripcion',        
        'estado',
        
    ];
}

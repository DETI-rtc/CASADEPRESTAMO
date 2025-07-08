<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resumendetalle extends Model
{
    use HasFactory;
    protected $fillable = [
        'envio_id',
        'comprobante_id',
        'condicion',
        'direccion',
        
    ];
}

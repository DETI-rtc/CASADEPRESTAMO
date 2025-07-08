<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalle extends Model
{
    use HasFactory;
    protected $table='detalle_comprobante';
    protected $fillable = [
        'comprobante_id',
        'item',
        'producto_id',
        'producto',
        'descripcion',
        'cantidad',
        'valor_unitario',
        'precio_unitario',
        'igv',
        'porcentaje_igv',
        'valor_total',
        'importe_total',
        
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditoDetalle extends Model
{
    use HasFactory;
    protected $table='credito_detalle';
    protected $fillable = [
        'id',
        'idcredito',
        'fecha_vencimiento',
        'fecha_pagado',
        'monto_deuda',
        'monto_pagado',
        'saldo',
        'nro_cuota',
        'situacion',
        'estado',
        'id_user',
        'idcomprobante',
    ];
    public function comprobantes() {
        return $this->hasMany('App\Models\Comprobante', 'idcuota');
    }
}

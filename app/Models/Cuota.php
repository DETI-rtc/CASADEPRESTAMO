<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuota extends Model
{
    protected $table='cuota';
    protected $fillable = [
        'fecha_vencimiento','saldo_capital','capital_amortizado','interes','com_desc_automático','cuota','seguro_degrav', 'idcronograma', 'estado','monto_pagado','cuota_restante'
    ];
}

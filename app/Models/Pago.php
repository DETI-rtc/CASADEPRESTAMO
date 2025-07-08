<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Persona;

class Pago extends Model
{
    protected $table='pago';
    protected $fillable = [
    'fecha_pago',
    'dni',
    'idcliente',
    'nom_cliente',
    'idcredito',
    'nro_cuota',
    'monto',
    'cap_amor',
    'interes',
    'seg_des',
    'com_des',
    'saldo',
    'mora',
    'estado',
    'dias_pos',
    'com_pago',
    'iduser',
    'monto_desc',
    'monto_efectivo',
    'idcuota',
    'tipo',
    'comentario',
    'situacion',
    'fec_pago_efec',
    'nro_recibo',
    'fecha_recibo',
    ];
    public function persona()
    {
        return $this->belongsto(Persona::class,'idcliente');
    }
}

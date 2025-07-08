<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cancelacredi extends Model
{
    use HasFactory;
    protected $table='credito_cancel';
   
     protected $fillable = [
    'idcredito','idsupervisor','idcliente','cliente','dni','monto_can','int_can','seg_can','com_can','feacha_reg','iduser','vaucher','fec_vaucher','fec_ven','apro_teso','estado', 'situacion', 'referencia','redondeo'
    ];
}

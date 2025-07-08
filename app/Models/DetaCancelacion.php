<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetaCancelacion extends Model
{
    use HasFactory;
    protected $table='credito_cancel';
   
     protected $fillable = [
        'idcredito',
        'idcancelacion',
        'vaucher',
        'estado',
        'situacion',
        'fec_voucher',
        'fec_ven',
    ];
}

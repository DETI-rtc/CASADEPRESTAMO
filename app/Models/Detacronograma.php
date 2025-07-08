<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Detacronograma extends Model
{
    protected $table='detallecrono';
    protected $fillable = [
     'idcrono','cuotanro','fecha_ven','sal_cap','cap_amor','interes','com_des','seg_des','cuota','diasnopago','mora','mon_pag','sal_pen','estado','situacion','iduser','amortizado'
    ];
}

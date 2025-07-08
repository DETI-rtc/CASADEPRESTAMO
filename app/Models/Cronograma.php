<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cronograma extends Model
{
    protected $table='cronograma';
    protected $fillable = [
     'iduser','idcliente','estado','idcredito','tasa_men_desgra','com_desc_auto','tasa_efe_anu', 't_interes', 'num_cuotas', 'periocidad',  'f_ultima_cuota', 'com_desembolso', 'mon_ne_desem', 'mon_financiado'
    ];


    public function detalle()
    {
        return $this->hasMany(Detacronograma::class,'idcrono');
    }

    public function cuotas()
    {
        return $this->hasMany(Detacronograma::class,'idcrono');
    }

    public function cliente()
    {
        return $this->hasMany(Persona::class,'idcliente');
    }
}

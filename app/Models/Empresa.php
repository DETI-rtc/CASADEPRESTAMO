<?php

namespace App\Models;
use App\Models\Empresa;
use App\Models\Delista;
use App\Models\Convenio;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table='empresa';
   
     protected $fillable = [
    'ruc','razonsocial','direccion','telefono','contacto','telefo_cont','estado','id_tipo','sector','iduser'
    ];
    public function convenio()
    {
        return $this->hasOne(Convenio::class,'idempresa');
    }

    public function tipoempresa()
    {
        return $this->belongsTo(Delista::class,'id_tipo');
    }
}

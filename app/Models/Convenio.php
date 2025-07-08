<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Empresa;

class Convenio extends Model
{
    protected $table='convenio';
   
     protected $fillable = [
    'nro','fecha_ini','fecha_fin','idempresa','entidad','estado','iduser'
    ];
    public function empresa()
    {
        return $this->belongsTo(Empresa::class,'idempresa');
    }

    
}

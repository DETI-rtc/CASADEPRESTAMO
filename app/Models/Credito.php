<?php

namespace App\Models;
use App\Models\Persona;
use App\Models\User;
use App\Models\Plazo;
use App\Models\Relacionci;
use App\Models\Relaciondi;
use App\Models\Cronograma;
use Illuminate\Database\Eloquent\Model;

class Credito extends Model
{
    protected $table='credito';
    protected $fillable = [
     'idpersona','meses_fal_cont','patrimonio','des_ley','ing_neto','idrci','ing_netodiscred','sal_deuda_sc','sal_deuda_cc','deuda_sc','deuda_cc','sal_hipo','deuda_hipo','cuota_max_pres','idplazo','plazo_mas_ope','primer_pago','idtasa_int','tem_rci','monto_max_rci','idrdi','max_ende','deuda_consu','otras_deudas','monto_max_rdi','monto_max_apro','plazo_mas_apro','tem','fec_apro','situacion','tipo','estado','repre_legal','dni_legal'
    ];
    public function persona()
    {
        return $this->belongsto(Persona::class,'idpersona');
    }

    public function analista()
    {
        return $this->hasMany(User::class,'id');
    }
    public function plazo()
    {
        return $this->belongsto(Delista::class,'idplazo');
    }
    public function rdi()
    {
        return $this->belongsto(Delista::class,'idrdi');
    }
    public function rci()
    {
        return $this->belongsto(Delista::class,'idrci');
    }
    public function tasainteres()
    {
        return $this->belongsto(Tasainteresa::class,'idtasa_int');
    }

    public function cronograma()
    {
        return $this->hasMany(Cronograma::class,'idcredito');
    }
}

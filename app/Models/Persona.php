<?php

namespace App\Models;
use App\Models\Delista;
use App\Models\Empresa;
use App\Models\Pago;
use App\Models\Credito;
use Illuminate\Database\Eloquent\Model;


class Persona extends Model
{
    protected $table='persona';
    protected $fillable = [
    'dni','paterno','materno','nombres','sexo','fec_nac', 'idestadocivil',
    'email','direccion','localidad','ocupacion','celular','referencia','ingre_bru',
    'idempresa','tipoentidad','tipomodalidad','idanalista','estado','escliente',
    'n_domicilio',
    'tipo_vivienda',
    'nivel_instruccion',
    'tipo_ocupacion',
    'f_ingreso_actividades',
    'f_fin_actividades',
    'modalidad_contratacion',
    'profesion',
    'cargo',
    'area','cci','cuenta_scotiabank','departamento','provincia','distrito','cci_banco','cuenta_banco'
    ];
    public function empresa()
    {
        return $this->belongsto(Empresa::class,'idempresa');
    }
    public function tentidad()
    {
        return $this->belongsto(Delista::class,'tipoentidad');
    }
    public function tmodalidad()
    {
        return $this->belongsto(Delista::class,'tipomodalidad');
    }
    public function credito()
    {
        return $this->hasMany(Credito::class,'idpersona');
    }
    public function pago()
    {
        return $this->hasMany(Pago::class,'idcliente');
    }

    public function estadocivil()
    {
        return $this->belongsto(Delista::class,'idestadocivil');
    }
    

}

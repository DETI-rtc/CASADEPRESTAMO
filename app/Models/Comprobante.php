<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comprobante extends Model
{
    use HasFactory;
    protected $table='comprobante';
    protected $fillable = [
        'emisor_id',
        'tipo_comprobante_id',
        'serie_id',
        'serie',
        'correlativo',
        'forma_pago',
        'fecha_emision',
        'fecha_vencimiento',
        'moneda_id',
        'op_gravadas',
        'op_exoneradas',
        'op_inafectas',
        'igv',
        'total',
        'cliente_id',
        'tipo_comprobante_ref_id',
        'serie_ref',
        'correlativo_ref',
        'codmotivo',
        'nombrexml',
        'xmlbase64',
        'codigo_sunat',
        'mensaje_sunat',
        'estado_comprobante',
        'idcuota',
        'id_detallecrono'
    ];

    public function detalle (){
        return $this->hasMany('App\Models\Detalle','comprobante_id');
    }
    public function cuota (){
        return $this->hasMany('App\Models\Cuota','comprobante_id');
    }
    public function serieRelacion(){
        return $this->hasOne('App\Models\Serie','id','serie_id');
    }
    
}

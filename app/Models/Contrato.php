<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    use HasFactory;
    protected $table='contrato';
    protected $fillable = [
        'id',
        'nro',
        'fecha',
        'monto',
        'tipo',
        'idcliente',
        'nombre',
        'apellido_pat',
        'apellido_mat',
        'dni',
        'ruc',
        'fecha_nac',
        'estado_civil',
        'nacionalidad',
        'telefono',
        'direccion',
        'dpto',
        'celular',
        'urb_etapa_sector',
        'departamento',
        'credo',
        'sexo',
        'ocupacion',
        'centro_trabajo',
        'direccion_trabajo',
        'provincia',
        'distrito',
        'telefono_trabajo',
        'email_particular',
        'email_trabajo',
        'nombre_conyuge',
        'apellido_pat_conyuge',
        'apellido_mat_conyuge',
        'numerodoc_conyuge',
        'fecha_nac_conyuge',
        'nacionalidad_conyuge',
        'telefono_conyuge',
        'celular_conyuge',
        'sexo_conyuge',
        'nombre_autorizado',
        'apellido_pat_autorizado',
        'apellido_mat_autorizado',
        'numerodoc_autorizado',
        'fecha_nac_autorizado',
        'nacionalidad_autorizado',
        'telefono_autorizado',
        'celular_autorizado',
        'sexo_autorizado',
        'tipo_sepultura',
        'capacidad_unidad',
        'capacidad_contratada',
        'escogida',
        'plataforma',
        'codigo_sepultura',
        'tipo_necesidad',
        'periodo_carencia',
        'fecha_termino_periodo_carencia',
        'costo_carencia',
        'minimo_inhumar',
        'precio_lista',
        'descuento',
        'precio_total',
        'fonto_mantencion',
        'vencimiento_fondo_mantencion',
        'contado',
        'pago_inicial',
        'pagare',
        'letra',
        'seguro',
        'monto_cuota',
        'monto_letra',
        'id_user',
        'estado',
        'direccion_recaudacion',
        'direccion_correspondencia',
        'tipo_direccion_recaudacion',
        'tipo_direccion_correspondencia',
    ];

    public function tipo_contrato() {
        return $this->hasOne('App\Models\TipoContrato', 'id','tipo');
    }
    public function distritos() {
        return $this->hasOne('App\Models\District', 'id','distrito');
    }
    public function provincias() {
        return $this->hasOne('App\Models\Province', 'id','provincia');
    }
}

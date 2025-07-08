<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Models\Hojaruta;

class DetalleHojaruta extends Model
{
    use HasFactory;
    protected $table='detalle_hojaruta';
    protected $fillable = [
        'idhoja','hora_ini','hora_fin','dni','cliente','actividad','status', 'horario', 'estado'
    ];
    public function arrayDetalle()
    {
        return $this->belongsto(Hojaruta::class,'idruta');
    }
}

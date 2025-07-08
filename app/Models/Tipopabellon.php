<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipopabellon extends Model
{
    use HasFactory;
    protected $table='cem_tipopabellon';
    public $incrementing = false;
    protected $fillable = [
        
        'descripcion',
        'estado',
        
    ];
}

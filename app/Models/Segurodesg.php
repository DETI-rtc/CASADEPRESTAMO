<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Segurodesg extends Model
{
    use HasFactory;
    protected $table='seguro_desg';
   
     protected $fillable = [
    'seguro_desg','estado'
    ];
}

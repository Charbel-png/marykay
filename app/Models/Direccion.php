<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Direccion extends Model
{
    protected $table = 'direcciones';
    protected $primaryKey = 'direccion_id';
    public $timestamps = false;

    protected $fillable = [
        'cliente_id',
        'etiqueta',
        'calle',
        'numero',
        'colonia',
        'ciudad',
        'estado',
        'cp',
        'pais',
    ];
}
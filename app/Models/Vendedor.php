<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendedor extends Model
{
    protected $table = 'vendedores';
    protected $primaryKey = 'vendedor_id';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'estatus',
        'fecha_alta',
        'supervisor_id',
    ];
}
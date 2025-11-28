<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedidos';
    protected $primaryKey = 'pedido_id';
    public $timestamps = false;

    protected $fillable = [
        'cliente_id',
        'vendedor_id',
        'fecha',
        'estado_id',
        'direccion_envio_id',
        'total',
    ];
}
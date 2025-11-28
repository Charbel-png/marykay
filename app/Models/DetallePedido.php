<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    protected $table = 'detalle_pedido';
    public $timestamps = false;

    // Esta tabla tiene PK compuesta (pedido_id, renglon)
    // Eloquent no maneja PK compuestas directamente, así que
    // vamos a trabajar con incrementing = false y sin primaryKey única.
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'pedido_id',
        'renglon',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'descuento',
        'iva_porcentaje',
    ];
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    protected $table = 'detalle_pedido';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = null; // Usamos la relación desde Pedido

    protected $fillable = [
        'pedido_id',
        'renglon',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'descuento',
        'iva_porcentaje',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id', 'pedido_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id', 'producto_id');
    }
}

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

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'cliente_id');
    }

    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class, 'vendedor_id', 'vendedor_id');
    }

    public function estado()
    {
        return $this->belongsTo(EstadoPedido::class, 'estado_id', 'estado_id');
    }

    public function direccionEnvio()
    {
        return $this->belongsTo(Direccion::class, 'direccion_envio_id', 'direccion_id');
    }

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class, 'pedido_id', 'pedido_id');
    }
}

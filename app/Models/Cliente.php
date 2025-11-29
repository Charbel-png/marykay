<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'cliente_id';
    public $timestamps = false;

    protected $fillable = [
        'nombres',
        'apellidos',
        'email',
        'telefono',
        'fecha_reg',
    ];

    public function direcciones()
    {
        return $this->hasMany(Direccion::class, 'cliente_id', 'cliente_id');
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'cliente_id', 'cliente_id');
    }

    public function getNombreCompletoAttribute()
    {
        return trim($this->nombres . ' ' . $this->apellidos);
    }
}

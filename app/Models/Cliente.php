<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'cliente_id';
    public $timestamps = false; // porque la tabla usa fecha_reg, no created_at/updated_at

    protected $fillable = [
        'nombres',
        'apellidos',
        'email',
        'telefono',
        'fecha_reg',
    ];

    protected $casts = [
        'fecha_reg' => 'datetime',
    ];

    public function direcciones()
    {
        return $this->hasMany(Direccion::class, 'cliente_id', 'cliente_id');
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'cliente_id', 'cliente_id');
    }

    // Accesor para usar $cliente->nombre_completo
    public function getNombreCompletoAttribute()
    {
        return trim($this->nombres . ' ' . $this->apellidos);
    }
}

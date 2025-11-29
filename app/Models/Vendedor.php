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

    public function supervisor()
    {
        return $this->belongsTo(Vendedor::class, 'supervisor_id', 'vendedor_id');
    }

    public function subordinados()
    {
        return $this->hasMany(Vendedor::class, 'supervisor_id', 'vendedor_id');
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'vendedor_id', 'vendedor_id');
    }
}

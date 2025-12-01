<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';
    protected $primaryKey = 'producto_id';
    public $timestamps = false;

    protected $fillable = 
    [
        'nombre',
        'sku',
        'descripcion',
        'precio_lista',
        'categoria_id',
        'unidad',
        'existencia',
        'imagen',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id', 'categoria_id');
    }
}

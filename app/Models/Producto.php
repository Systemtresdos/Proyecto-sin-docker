<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Producto extends Model
{
    use HasFactory;
    
    protected $fillable = 
    ['nombre',
    'descripcion',
    'codigo',
    'precio_venta',
    'imagen',
    'estado',
    'puntuacion',
    'categoria_id',
];

    public function categoria(){
        return $this->belongsTo(Categoria::class);
    }
    
    public function detallePedido(){
        return $this->hasMany(DetallePedido::class);
    }

    public function pedido(){
        return $this->belongsToMany(Pedido::class,'detalle_pedidos','pedido_id','producto_id')
        ->withPivot('cantidad','descuento','precio_unitario','sub_total');
    }
}

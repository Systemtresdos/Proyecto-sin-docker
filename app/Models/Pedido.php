<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = [
        'nro_pedido',
        'estado',
        'tipo_entrega',
        'metodo_pago',
        'direccion_entrega',
        'notas_adicionales',
        'total',
        'cliente_id',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function productos()
    {
        return $this->belongsToMany(Producto::class,'detalle_pedidos', 'pedido_id', 'producto_id')
        ->withPivot('cantidad','descuento','precio_unitario','sub_total');
    }

    public function detallePedidos()
    {
        return $this->hasMany(DetallePedido::class);
    }

    public function pago()
    {
        return $this->hasOne(Pago::class);
    }
}

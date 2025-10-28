<?php

namespace Database\Seeders;

use App\Models\Producto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Producto::create([
            'nombre' => 'Hamburguesa Clásica',
            'descripcion' => 'Una deliciosa hamburguesa con carne de res, lechuga, tomate y cebolla.',
            'codigo' => 'HAMB001',
            'precio_venta' => 20,
            'imagen' => 'productos\fK2Snm7CwGzzPdTaG9GXoxRjvyDoNu2TOJBY606H.jpg',
            'estado' => 'disponible',
            'calificacion' => 3,
            'categoria_id' => 1,
        ]);
        Producto::create([
            'nombre' => 'Hamburguesa doble',
            'descripcion' => 'Una hamburguesa doble con queso, tocino y salsa especial.',
            'codigo' => 'HAMB002',
            'precio_venta' => 30,
            'imagen' => 'productos\RdqHz0jiHD9oXPGJebOMOTQIcQbcB5ZDCqFmmJQj.jpg',
            'estado' => 'disponible',
            'calificacion' => 5,
            'categoria_id' => 1,
        ]);
        Producto::create([
            'nombre' => 'Hamburguresa Premium',
            'descripcion' => 'Una hamburguesa gourmet con ingredientes frescos y de alta calidad.',
            'codigo' => 'HAMB003',
            'precio_venta' => 50,
            'imagen' => 'productos\kjt8d4B3DBnXiZ5EcA6nkysmLxqfSyTXV7ls6aS3.jpg',
            'estado' => 'disponible',
            'calificacion' => 5,
            'categoria_id' => 1,
        ]);
        Producto::create([
            'nombre' => 'Pizza Margarita',
            'descripcion' => 'Pizza con salsa de tomate, queso mozzarella y albahaca fresca.',
            'codigo' => 'PIZZA001',
            'precio_venta' => 40,
            'imagen' => 'productos\wIutC7bpn5Xs8jDYq4TJcO695KlscRa36mNpiiXb.jpg',
            'estado' => 'disponible',
            'calificacion' => 4,
            'categoria_id' => 2,
        ]);
        Producto::create([
            'nombre' => 'Pizza Pepperoni',
            'descripcion' => 'Pizza con salsa de tomate, queso mozzarella y rodajas de pepperoni.',
            'codigo' => 'PIZZA002',
            'precio_venta' => 45,
            'imagen' => 'productos\ma9W1JTtdCayreSePy8VqiT1ztsYz1tGrYE8zfUB.jpg',
            'estado' => 'disponible',
            'calificacion' => 5,
            'categoria_id' => 2,
        ]);
        Producto::create([
            'nombre' => 'Pizza clasica',
            'descripcion' => 'Una pizza clásica con una mezcla de quesos, jamon, peperoni y salsa de tomate.',
            'codigo' => 'PIZZA003',
            'precio_venta' => 35,
            'imagen' => 'productos\PfWAmE7CA7HC63MltmSAJY623QQSexdZIcf7WzXO.jpg',
            'estado' => 'disponible',
            'calificacion' => 4,
            'categoria_id' => 2,
        ]);
        Producto::create([
            'nombre' => 'Pollo frito Duo',
            'descripcion' => 'Una porción de pollo frito con dos piezas crujientes y sabrosas.',
            'codigo' => 'POLLO001',
            'precio_venta' => 25,
            'imagen' => 'productos\yzKC6R52p0MJ3aJSTXeqlhnAyU415Lq61SQxTnMS.jpg',
            'estado' => 'disponible',
            'calificacion' => 4,
            'categoria_id' => 3,
        ]);
        Producto::create([
            'nombre' => 'Pollo frito Premium',
            'descripcion' => 'Una porción de pollo frito con tres piezas, cuatro nuggest y pipocas.',
            'codigo' => 'POLLO002',
            'precio_venta' => 35,
            'imagen' => 'productos\TB84lwHLrfcBHcM19j9T3mVVSMGOan3gixKdX54e.jpg',
            'estado' => 'disponible',
            'calificacion' => 5,
            'categoria_id' => 3,
        ]);
    }
}

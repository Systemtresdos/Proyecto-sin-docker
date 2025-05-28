<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Categoria::create([
            'nombre' => 'Hamburguesas',
            'descripcion' => 'Deliciosas hamburguesas con una variedad de ingredientes.',
        ]);
        Categoria::create([
            'nombre' => 'Pizzas',
            'descripcion' => 'Pizzas recién horneadas con ingredientes frescos.',
        ]);
        Categoria::create([
            'nombre' => 'Pollos fritos',
            'descripcion' => 'Jugosos pollos fritos, perfectos para cualquier ocasión.',
        ]);
        Categoria::create([
            'nombre' => 'Sandwiches',
            'descripcion' => 'Sabrosos sandwiches con una variedad de rellenos.',
        ]);
    }
}

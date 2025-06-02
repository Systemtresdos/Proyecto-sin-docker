<?php

namespace Database\Seeders;

use App\Models\Encargado;
use App\Models\Usuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Usuario::create([
            'nombre' => 'Administrador',
            'telefono' => '1111111111',
            'direccion' => 'Calle Falsa 123',
            'email' => 'arleth@gmail.com',
            'password' => bcrypt('123456789'),
            'rol_id' => 1,
        ]);
        Encargado::create([
            'usuario_id' => 1,
            'dni' => '000000000'
        ]);
    }
}

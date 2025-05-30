<?php

use App\Models\Categoria;

it('puede crear una categoría', function () {
    $data = [
        'nombre' => 'Bebidas',
        'descripcion' => 'Todas las bebidas frías y calientes',
    ];

    $categoria = Categoria::create($data);

    expect($categoria)->toBeInstanceOf(Categoria::class)
        ->and($categoria->nombre)->toBe('Bebidas')
        ->and($categoria->descripcion)->toBe('Todas las bebidas frías y calientes');
});

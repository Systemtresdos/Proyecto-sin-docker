<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use PhpParser\Node\Stmt\Return_;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = ['nombre','descripcion'];

    public function producto(){
        return $this->hasMany(Producto::class);
    }
}

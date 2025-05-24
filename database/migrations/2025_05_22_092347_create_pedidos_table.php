<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->string('nro_pedido');
            $table->enum('estado', ['Pendiente', 'Confirmado', 'Preparando', 'Entregado'])->default('Pendiente');
            $table->enum('tipo_entrega', ['Domicilio', 'Retiro_local'])->default('Domicilio');
            $table->string('direccion_entrega');
            $table->string('notas_adicionales')->nullable();
            $table->decimal('total', 10, 2);
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};

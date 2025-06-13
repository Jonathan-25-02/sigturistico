
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion');
            $table->enum('categoria', ['cultural', 'natural', 'historico', 'gastronomico', 'aventura']);
            $table->string('imagen')->nullable();
            $table->decimal('latitud', 10, 8);
            $table->decimal('longitud', 11, 8);
            $table->timestamps();
            
            // Índices para búsquedas más rápidas
            $table->index(['categoria']);
            $table->index(['latitud', 'longitud']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('clientes');
    }
};
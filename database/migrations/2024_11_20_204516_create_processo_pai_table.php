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
        Schema::create('PROCESSOPAI', function (Blueprint $table) {
           
            $table->id('id'); // ID primário
            $table->string('NPROCPAI')->unique(); // Número do processo pai (único)
            $table->decimal('VALORTOTAL', 18, 2); // Valor total com precisão
            $table->string('NUMEROAPROVACAO')->nullable(); // Número de aprovação (opcional)
            $table->string('STATUSPROCESSO'); // Status do processo
            $table->decimal('SALDO', 18, 2); // Saldo com precisão
            $table->timestamps(); // Colunas de created_at e updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('PROCESSOPAI');
    }
};

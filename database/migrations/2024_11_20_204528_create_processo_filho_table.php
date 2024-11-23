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
        Schema::create('PROCESSOFILHO', function (Blueprint $table) {
            $table->id('id'); // ID primário
            $table->foreignId('PROCESSOPAI_ID') // Chave estrangeira para PROCESSOPAI
                ->constrained('PROCESSOPAI')
                ->onDelete('cascade');
            $table->string('NPROCFILHO'); // Número do processo filho
            $table->string('NPROCPAI'); // Número do processo pai (redundância, opcional)
            $table->decimal('VALOR', 18, 2); // Valor com precisão
            $table->string('NUMEROAPROVACAO')->nullable(); // Número de aprovação (opcional)
            $table->string('STATUSPROCESSO'); // Status do processo
            $table->timestamps(); // Colunas de created_at e updated_at

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('PROCESSOFILHO');
    }
};

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
        Schema::create('processo_filho', function (Blueprint $table) {
            $table->id();
            $table->foreignId('processo_pai_id')->constrained('processo_pai')->onDelete('cascade');
            $table->decimal('valor', 18, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('processo_filho');
    }
};

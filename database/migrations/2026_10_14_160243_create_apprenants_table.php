<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('apprenants', function (Blueprint $table) {
            $table->id();
            $table->string('nomComplet');
            $table->enum('sexe', ['M', 'F']);
            $table->enum('niveau', [1, 2, 3]);
            $table->foreignId('groupe_id')
                  ->nullable()
                  ->constrained('groupes')
                  ->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apprenants');
    }
};
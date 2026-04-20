<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');

            // Infos principales
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->decimal('price', 10, 2);

            // État du produit ✅ corrigé
            $table->enum('condition', ['neuf', 'tres_bon', 'bon', 'acceptable'])->default('bon');

            // Statut de l'annonce ✅ corrigé
            $table->enum('status', ['disponible', 'vendu', 'suspendu'])->default('disponible');

            // Statistiques
            $table->integer('views')->default(0);

            // Optionnel
            $table->string('location')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
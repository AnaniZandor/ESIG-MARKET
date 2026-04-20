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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
                // utilisateur qui note
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');

            // utilisateur noté (vendeur)
            $table->foreignId('reviewed_id')->constrained('users')->onDelete('cascade');

            // article concerné
            $table->foreignId('article_id')->constrained()->onDelete('cascade');

            // note
            $table->tinyInteger('rating');

            // commentaire
            $table->text('comment')->nullable();
            $table->timestamps();
                // empêcher doublon d'avis
            $table->unique(['reviewer_id', 'reviewed_id', 'article_id']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};

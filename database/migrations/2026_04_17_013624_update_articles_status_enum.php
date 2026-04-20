<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->enum('status', ['disponible', 'vendu', 'suspendu'])
                  ->default('disponible')
                  ->change();

            $table->enum('condition', ['neuf', 'tres_bon', 'bon', 'acceptable'])
                  ->default('bon')
                  ->change();
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->enum('status', ['draft', 'published', 'sold'])
                  ->default('draft')
                  ->change();
        });
    }
};
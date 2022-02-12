<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryFilmTable extends Migration
{
    /**
     * Run the migrations for the category_film table.
     */
    public function up(): void
    {
        Schema::create('category_film', function (Blueprint $table) {
            $table->foreignId('category_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('film_id')->index()->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->primary(['category_id', 'film_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations for the category_film table.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_film');
    }
}

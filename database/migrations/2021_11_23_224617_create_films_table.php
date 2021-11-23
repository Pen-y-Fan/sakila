<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilmsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('films', function (Blueprint $table) {
            $table->id();

            $table->string('title', 128)->index();
            $table->text('description')->nullable();
            $table->year('release_year')->nullable();
            $table->foreignId('language_id')->cascadeOnUpdate()->constrained('languages');
            $table->foreignId('original_language_id')->nullable()->cascadeOnUpdate()->constrained('languages');
            $table->tinyInteger('rental_duration')->default('3');
            $table->decimal('rental_rate', 4, 2)->default('4.99');
            $table->smallInteger('length')->nullable();
            $table->decimal('replacement_cost', 5, 2)->default('19.99');
            $table->enum('rating', ['G', 'PG', 'PG-13', 'R', 'NC-17'])->nullable()->default('G');
            $table->set('special_features', ['Trailers', 'Commentaries', 'Deleted Scenes', 'Behind the Scenes'])
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('films');
    }
}

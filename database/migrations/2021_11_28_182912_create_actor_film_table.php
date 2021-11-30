<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActorFilmTable extends Migration
{
    /**
     * Run the migrations for the actor_film table.
     */
    public function up(): void
    {
        Schema::create('actor_film', function (Blueprint $table) {
            $table->foreignId('actor_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('film_id')->index()->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->primary(['actor_id', 'film_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations for the actor_film table.
     */
    public function down(): void
    {
        Schema::dropIfExists('actor_film');
    }
}

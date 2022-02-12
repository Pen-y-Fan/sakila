<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTable extends Migration
{
    /**
     * Run the migrations for the inventories table.
     */
    public function up(): void
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('film_id')->index()->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('store_id')->index()->constrained()->cascadeOnUpdate()->restrictOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations for the inventories table.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
}

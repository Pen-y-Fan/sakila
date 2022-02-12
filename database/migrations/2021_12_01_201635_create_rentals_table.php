<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRentalsTable extends Migration
{
    /**
     * Run the migrations for the rentals table.
     */
    public function up(): void
    {
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();

            $table->dateTime('rental_date');
            $table->foreignId('inventory_id')->index()->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('customer_id')->index()->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->dateTime('return_date')->nullable();
            $table->foreignId('staff_id')->index()->constrained()->cascadeOnUpdate()->restrictOnDelete();

            $table->unique(['rental_date', 'inventory_id', 'customer_id'], 'rental_date');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations for the rentals table.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
}

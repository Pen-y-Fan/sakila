<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations for the customers table.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('store_id')->index()->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->string('first_name', 45);
            $table->string('last_name', 45)->index();
            $table->string('email', 50)->nullable();
            $table->foreignId('address_id')->index()->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->boolean('active')->default('1');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations for the customers table.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
}

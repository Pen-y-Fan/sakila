<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations for the payments table.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')->index()->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('staff_id')->index()->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('rental_id')->nullable()->index()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->decimal('amount', 5, 2);
            $table->dateTime('payment_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations for the payments table.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
}

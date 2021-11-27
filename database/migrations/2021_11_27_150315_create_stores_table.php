<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();

            $table->foreignId('address_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            /*
                        `manager_staff_id` TINYINT(3) UNSIGNED NOT NULL,
                        `address_id` SMALLINT(5) UNSIGNED NOT NULL,
                        UNIQUE INDEX `idx_unique_manager` (`manager_staff_id`) USING BTREE,
                        INDEX `idx_fk_address_id` (`address_id`) USING BTREE,
                        CONSTRAINT `fk_store_address` FOREIGN KEY (`address_id`) REFERENCES `sakila`.`address` (`address_id`) ON UPDATE CASCADE ON DELETE RESTRICT,
                        CONSTRAINT `fk_store_staff` FOREIGN KEY (`manager_staff_id`) REFERENCES `sakila`.`staff` (`staff_id`) ON UPDATE CASCADE ON DELETE RESTRICT
            */

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
}

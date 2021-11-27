<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffTable extends Migration
{
    /**
     * Run the migrations for the staff table.
     */
    public function up(): void
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->id();

            $table->string('first_name', 45);
            $table->string('last_name', 45);
            $table->foreignId('address_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->string('picture')->nullable();
            $table->string('email', 50)->nullable();
            $table->foreignId('store_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->boolean('active')->default('1');
            $table->string('username', 16);
            $table->string('password', 40)->nullable();

            /*
                `first_name` VARCHAR(45) NOT NULL COLLATE 'utf8mb4_general_ci',
                `last_name` VARCHAR(45) NOT NULL COLLATE 'utf8mb4_general_ci',
                `address_id` SMALLINT(5) UNSIGNED NOT NULL,
                `picture` BLOB NULL DEFAULT NULL,
                `email` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                `store_id` TINYINT(3) UNSIGNED NOT NULL,
                `active` TINYINT(1) NOT NULL DEFAULT '1',
                `username` VARCHAR(16) NOT NULL COLLATE 'utf8mb4_general_ci',
                `password` VARCHAR(40) NULL DEFAULT NULL COLLATE 'utf8mb4_bin',
                `last_update` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`staff_id`) USING BTREE,
                INDEX `idx_fk_store_id` (`store_id`) USING BTREE,
                INDEX `idx_fk_address_id` (`address_id`) USING BTREE,
                CONSTRAINT `fk_staff_address` FOREIGN KEY (`address_id`) REFERENCES `sakila`.`address` (`address_id`) ON UPDATE CASCADE ON DELETE RESTRICT,
                CONSTRAINT `fk_staff_store` FOREIGN KEY (`store_id`) REFERENCES `sakila`.`store` (`store_id`) ON UPDATE CASCADE ON DELETE RESTRICT
            */

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations for the staff table.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
}

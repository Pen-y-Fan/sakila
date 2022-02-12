<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(
            [
                CountrySeeder::class,
                LanguageSeeder::class,
                FilmSeeder::class,
                ActorSeeder::class,
                CitySeeder::class,
                AddressSeeder::class,
                StoreSeeder::class,
                StaffSeeder::class,
                CategorySeeder::class,
                CategoryFilmSeeder::class,
                ActorFilmSeeder::class,
                CustomerSeeder::class,
                InventorySeeder::class,
                RentalSeeder::class,
                PaymentSeeder::class,
            ]
        );
    }
}

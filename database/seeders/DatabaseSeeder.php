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
        // \App\Models\User::factory(10)->create();
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
                CategoryFilmSeeder::class,
                ActorFilmSeeder::class,
                CustomerSeeder::class,
            ]
        );
    }
}

<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use JeroenZwart\CsvSeeder\CsvSeeder;

class ActorFilmSeeder extends CsvSeeder
{
    public $truncate = false;

    public function __construct()
    {
        $this->file = '/database/seeders/actor_film.csv';
        $this->aliases = ['last_update' => 'created_at'];
        $this->timestamps = '2006-02-15 05:05:03';
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Recommended when importing larger CSVs
        DB::disableQueryLog();
        parent::run();
    }
}

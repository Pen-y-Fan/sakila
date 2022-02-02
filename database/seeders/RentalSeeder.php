<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use JeroenZwart\CsvSeeder\CsvSeeder;

class RentalSeeder extends CsvSeeder
{
    public $truncate = false;

    public function __construct()
    {
        $this->file = '/database/seeders/rentals.csv';
        $this->chunk = 500;
        $this->aliases = [
            'rental_id'  => 'id',
            'last_update' => 'created_at'
        ];
        $this->timestamps = '2006-02-15 21:30:53';
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Recommended when importing larger CSVs
        DB::disableQueryLog();
        parent::run();
    }
}

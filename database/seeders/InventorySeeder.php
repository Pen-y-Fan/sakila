<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use JeroenZwart\CsvSeeder\CsvSeeder;

class InventorySeeder extends CsvSeeder
{
    public function __construct()
    {
        $this->file = '/database/seeders/inventories.csv';
        $this->chunk = 500;
        $this->aliases = [
            'inventory_id' => 'id',
            'last_update' => 'created_at'
        ];
        $this->timestamps = '2006-02-15 05:09:17';
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

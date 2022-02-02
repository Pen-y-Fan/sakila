<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use JeroenZwart\CsvSeeder\CsvSeeder;

class PaymentSeeder extends CsvSeeder
{
    public $truncate = false;

    public function __construct()
    {
        $this->file = '/database/seeders/payments.csv';
        $this->chunk = 500;
        $this->aliases = [
            'payment_id' => 'id',
            'last_update' => 'created_at'
        ];
        $this->timestamps = '2006-02-15 22:12:30';
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

<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $store = [
            [
                'id'               => 1,
                'manager_staff_id' => 1,
                'address_id'       => 1,
                'created_at'       => '2006-02-15 04:57:12',
                'updated_at'       => '2006-02-15 04:57:12',
            ],
            [
                'id'               => 2,
                'manager_staff_id' => 2,
                'address_id'       => 2,
                'created_at'       => '2006-02-15 04:57:12',
                'updated_at'       => '2006-02-15 04:57:12',
            ],
        ];

        DB::table('stores')->insert($store);
    }
}

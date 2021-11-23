<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $language = [
            [
                'id'         => 1,
                'name'       => 'English',
                'created_at' => '2006-02-15 05:02:19',
                'updated_at' => '2006-02-15 05:02:19',
            ],
            [
                'id'         => 2,
                'name'       => 'Italian',
                'created_at' => '2006-02-15 05:02:19',
                'updated_at' => '2006-02-15 05:02:19',
            ],
            [
                'id'         => 3,
                'name'       => 'Japanese',
                'created_at' => '2006-02-15 05:02:19',
                'updated_at' => '2006-02-15 05:02:19',
            ],
            [
                'id'         => 4,
                'name'       => 'Mandarin',
                'created_at' => '2006-02-15 05:02:19',
                'updated_at' => '2006-02-15 05:02:19',
            ],
            [
                'id'         => 5,
                'name'       => 'French',
                'created_at' => '2006-02-15 05:02:19',
                'updated_at' => '2006-02-15 05:02:19',
            ],
            [
                'id'         => 6,
                'name'       => 'German',
                'created_at' => '2006-02-15 05:02:19',
                'updated_at' => '2006-02-15 05:02:19',
            ],
        ];
        DB::table('languages')->insert($language);
    }
}

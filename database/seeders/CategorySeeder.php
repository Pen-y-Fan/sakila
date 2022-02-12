<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category = [
            [
                'id'         => 1,
                'name'       => 'Action',
                'created_at' => '2006-02-15 04:46:27',
                'updated_at' => '2006-02-15 04:46:27',
            ],
            [
                'id'         => 2,
                'name'       => 'Animation',
                'created_at' => '2006-02-15 04:46:27',
                'updated_at' => '2006-02-15 04:46:27',
            ],
            [
                'id'         => 3,
                'name'       => 'Children',
                'created_at' => '2006-02-15 04:46:27',
                'updated_at' => '2006-02-15 04:46:27',
            ],
            [
                'id'         => 4,
                'name'       => 'Classics',
                'created_at' => '2006-02-15 04:46:27',
                'updated_at' => '2006-02-15 04:46:27',
            ],
            [
                'id'         => 5,
                'name'       => 'Comedy',
                'created_at' => '2006-02-15 04:46:27',
                'updated_at' => '2006-02-15 04:46:27',
            ],
            [
                'id'         => 6,
                'name'       => 'Documentary',
                'created_at' => '2006-02-15 04:46:27',
                'updated_at' => '2006-02-15 04:46:27',
            ],
            [
                'id'         => 7,
                'name'       => 'Drama',
                'created_at' => '2006-02-15 04:46:27',
                'updated_at' => '2006-02-15 04:46:27',
            ],
            [
                'id'         => 8,
                'name'       => 'Family',
                'created_at' => '2006-02-15 04:46:27',
                'updated_at' => '2006-02-15 04:46:27',
            ],
            [
                'id'         => 9,
                'name'       => 'Foreign',
                'created_at' => '2006-02-15 04:46:27',
                'updated_at' => '2006-02-15 04:46:27',
            ],
            [
                'id'         => 10,
                'name'       => 'Games',
                'created_at' => '2006-02-15 04:46:27',
                'updated_at' => '2006-02-15 04:46:27',
            ],
            [
                'id'         => 11,
                'name'       => 'Horror',
                'created_at' => '2006-02-15 04:46:27',
                'updated_at' => '2006-02-15 04:46:27',
            ],
            [
                'id'         => 12,
                'name'       => 'Music',
                'created_at' => '2006-02-15 04:46:27',
                'updated_at' => '2006-02-15 04:46:27',
            ],
            [
                'id'         => 13,
                'name'       => 'New',
                'created_at' => '2006-02-15 04:46:27',
                'updated_at' => '2006-02-15 04:46:27',
            ],
            [
                'id'         => 14,
                'name'       => 'Sci-Fi',
                'created_at' => '2006-02-15 04:46:27',
                'updated_at' => '2006-02-15 04:46:27',
            ],
            [
                'id'         => 15,
                'name'       => 'Sports',
                'created_at' => '2006-02-15 04:46:27',
                'updated_at' => '2006-02-15 04:46:27',
            ],
            [
                'id'         => 16,
                'name'       => 'Travel',
                'created_at' => '2006-02-15 04:46:27',
                'updated_at' => '2006-02-15 04:46:27',
            ],
        ];

        DB::table('categories')->insert($category);
    }
}

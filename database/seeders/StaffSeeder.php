<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $staff = [
            [
                'id'         => 1,
                'first_name' => 'Mike',
                'last_name'  => 'Hillyer',
                'address_id' => 3,
                'picture'    => null,
                'email'      => 'Mike.Hillyer@sakilastaff.com',
                'store_id'   => 1,
                'active'     => 1,
                'username'   => 'Mike',
                'password'   => '8cb2237d0679ca88db6464eac60da96345513964',
                'created_at' => '2006-02-15 03:57:16',
                'updated_at' => '2006-02-15 03:57:16',
            ],
            [
                'id'         => 2,
                'first_name' => 'Jon',
                'last_name'  => 'Stephens',
                'address_id' => 4,
                'picture'    => null,
                'email'      => 'Jon.Stephens@sakilastaff.com',
                'store_id'   => 2,
                'active'     => 1,
                'username'   => 'Jon',
                'password'   => null,
                'created_at' => '2006-02-15 03:57:16',
                'updated_at' => '2006-02-15 03:57:16',
            ],
        ];

        DB::table('staff')->insert($staff);
    }
}

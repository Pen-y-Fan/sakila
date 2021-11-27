<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

/**
 * Understanding JOINS in SQL and translating them in Query Builder
 * Based on
 * https://www.youtube.com/watch?v=kmVpfwN3vWs&list=PLkyrdyGDWthC-yd9n8R3CEauJC4sFl-kj&index=5
 */
class UnderstandingJoinsInSqlAndTranslatingThemInQueryBuilderTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testJoiningStaffToAddressToCityToCountry(): void
    {
        self::markTestSkipped('to be converted to model');
        /*
            SELECT s.staff_id,
                   s.first_name,
                   s.last_name,
                   s.email,
                   a.address,
                   a.district,
                   a.postal_code,
                   c.city,
                   co.country
            FROM staff AS s
                     LEFT JOIN address AS a ON s.address_id = a.address_id
                     LEFT JOIN city AS c ON a.city_id = c.city_id
                     LEFT JOIN country AS co ON co.country_id = c.country_id
        */

        $staff = DB::table('staff AS s')
            ->select(
                [
                    's.staff_id',
                    's.first_name',
                    's.last_name',
                    's.email',
                    'a.address',
                    'a.district',
                    'a.postal_code',
                    'ci.city',
                    'co.country',
                ]
            )
            ->leftJoin('address AS a', 's.address_id', '=', 'a.address_id')
            ->leftJoin('city AS ci', 'a.city_id', '=', 'ci.city_id')
            ->leftJoin('country AS co', 'ci.country_id', '=', 'co.country_id')
            ->get();

        Log::info('Joining staff to address to city to country', [$staff]);

        self::assertCount(2, $staff);

        /*
        [2021-11-04 23:03:51] testing.INFO: Joining staff to address to city to country
        [{"Illuminate\\Support\\Collection":[
        {"staff_id":1,"first_name":"Mike","last_name":"Hillyer","email":"Mike.Hillyer@sakilastaff.com","address":"23 Workhaven Lane","district":"Alberta","postal_code":"","city":"Lethbridge","country":"Canada"},
        {"staff_id":2,"first_name":"Jon","last_name":"Stephens","email":"Jon.Stephens@sakilastaff.com","address":"1411 Lillydale Drive","district":"QLD","postal_code":"","city":"Woodridge","country":"Australia"}
        ]}]
        */
    }
}

<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Staff;
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

    public function testLaravelWayToJoinModels(): void
    {
        $staff = Staff::select(['id', 'first_name', 'last_name', 'email', 'address_id'])
            ->with(
                [
                    'address:id,address,district,city_id',
                    'address.city:id,city,country_id',
                    'address.city.country:id,country',
                ]
            )
            ->get();

        Log::info('Joining staff to address to city to country', [$staff]);

        self::assertCount(2, $staff);

        $firstStaff = $staff->first();

        $this->assertSame('Mike', $firstStaff->first_name);
        $this->assertSame('23 Workhaven Lane', $firstStaff->address->address);
        $this->assertSame('Alberta', $firstStaff->address->district);
        $this->assertSame('Lethbridge', $firstStaff->address->city->city);
        $this->assertSame('Canada', $firstStaff->address->city->country->country);

        /*
         *

        [2021-11-27 20:59:28] testing.INFO: Joining staff to address to city to country
        [{"Illuminate\\Database\\Eloquent\\Collection":
        [{"id":1,"first_name":"Mike","last_name":"Hillyer","email":"Mike.Hillyer@sakilastaff.com","address_id":3,
        "address":{"id":3,"address":"23 Workhaven Lane","district":"Alberta","city_id":300,
        "city":{"id":300,"city":"Lethbridge","country_id":20,
        "country":{"id":20,"country":"Canada"}}}},

        {"id":2,"first_name":"Jon","last_name":"Stephens","email":"Jon.Stephens@sakilastaff.com","address_id":4,
        "address":{"id":4,"address":"1411 Lillydale Drive","district":"QLD","city_id":576,
        "city":{"id":576,"city":"Woodridge","country_id":8,
        "country":{"id":8,"country":"Australia"}}}}
        ]}]

        */
    }

    public function testJoiningStaffToAddressToCityToCountry(): void
    {
//        self::markTestSkipped('to be converted to model');
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
                    's.id',
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
            ->leftJoin('addresses AS a', 'a.id', '=', 's.address_id')
            ->leftJoin('cities AS ci', 'a.city_id', '=', 'ci.id')
            ->leftJoin('countries AS co', 'ci.country_id', '=', 'co.id')
            ->get();

        Log::info('Joining staff to address to city to country', [$staff]);

        self::assertCount(2, $staff);

        $firstStaff = $staff->first();

        $this->assertSame('Mike', $firstStaff->first_name);
        $this->assertSame('23 Workhaven Lane', $firstStaff->address);
        $this->assertSame('Alberta', $firstStaff->district);
        $this->assertSame('Lethbridge', $firstStaff->city);
        $this->assertSame('Canada', $firstStaff->country);


        /*
            [2021-11-27 19:53:03] testing.INFO: Joining staff to address to city to country [
        {"Illuminate\\Support\\Collection":[
        {"id":1,"first_name":"Mike","last_name":"Hillyer","email":"Mike.Hillyer@sakilastaff.com","address":"23 Workhaven Lane","district":"Alberta","postal_code":"","city":"Lethbridge","country":"Canada"},
        {"id":2,"first_name":"Jon","last_name":"Stephens","email":"Jon.Stephens@sakilastaff.com","address":"1411 Lillydale Drive","district":"QLD","postal_code":"","city":"Woodridge","country":"Australia"}
        ]}]


        */
    }
}

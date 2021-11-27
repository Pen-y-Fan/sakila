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

        $staff = Staff::with('address')
            ->with('address.city')
            ->with('address.city.country')
            ->get();

        Log::info('Joining staff to address to city to country', [$staff]);

        self::assertCount(2, $staff);

        $firstStaff = $staff->first();

        $this->assertSame('Mike', $firstStaff->first_name);
        $this->assertSame('23 Workhaven Lane', $firstStaff->address->address);
        $this->assertSame('Alberta', $firstStaff->address->district);
        $this->assertSame('Lethbridge', $firstStaff->address->city->city);
        $this->assertSame('Canada', $firstStaff->address->city->country->country);

//        $staff = DB::table('staff AS s')
//            ->select(
//                [
//                    's.staff_id',
//                    's.first_name',
//                    's.last_name',
//                    's.email',
//                    'a.address',
//                    'a.district',
//                    'a.postal_code',
//                    'ci.city',
//                    'co.country',
//                ]
//            )
//            ->leftJoin('address AS a', 's.address_id', '=', 'a.address_id')
//            ->leftJoin('city AS ci', 'a.city_id', '=', 'ci.city_id')
//            ->leftJoin('country AS co', 'ci.country_id', '=', 'co.country_id')
//            ->get();


        /*

        [2021-11-27 20:09:56] testing.INFO: Joining staff to address to city to country
        [{"Illuminate\\Database\\Eloquent\\Collection":

        [{"id":1,"first_name":"Mike","last_name":"Hillyer","address_id":3,"picture":null,"email":"Mike.Hillyer@sakilastaff.com","store_id":1,"active":1,"username":"Mike","created_at":"2006-02-15T03:57:16.000000Z","updated_at":"2006-02-15T03:57:16.000000Z",
        "address":{"id":3,"address":"23 Workhaven Lane","address2":null,"district":"Alberta","city_id":300,"postal_code":"","phone":"14033335568","location":"0x000000000101000000CDC4196863345CC01DEE7E7099D94840","created_at":"2014-09-25T22:30:27.000000Z","updated_at":"2014-09-25T22:30:27.000000Z",
        "city":{"id":300,"city":"Lethbridge","country_id":20,"created_at":"2006-02-15T04:45:25.000000Z","updated_at":"2006-02-15T04:45:25.000000Z",
        "country":{"id":20,"country":"Canada","created_at":"2006-02-15T04:44:00.000000Z","updated_at":"2006-02-15T04:44:00.000000Z"}}}},

        {"id":2,"first_name":"Jon","last_name":"Stephens","address_id":4,"picture":null,"email":"Jon.Stephens@sakilastaff.com","store_id":2,"active":1,"username":"Jon","created_at":"2006-02-15T03:57:16.000000Z","updated_at":"2006-02-15T03:57:16.000000Z",
        "address":{"id":4,"address":"1411 Lillydale Drive","address2":null,"district":"QLD","city_id":576,"postal_code":"","phone":"6172235589","location":"0x0000000001010000005B0DE4341F26634042D6AE6422A23BC0","created_at":"2014-09-25T22:30:09.000000Z","updated_at":"2014-09-25T22:30:09.000000Z",
        "city":{"id":576,"city":"Woodridge","country_id":8,"created_at":"2006-02-15T04:45:25.000000Z","updated_at":"2006-02-15T04:45:25.000000Z",
        "country":{"id":8,"country":"Australia","created_at":"2006-02-15T04:44:00.000000Z","updated_at":"2006-02-15T04:44:00.000000Z"}

        }}}]}]



        [2021-11-27 19:36:49] testing.INFO: Joining staff to address to city to country
        [{"Illuminate\\Database\\Eloquent\\Collection":[

        {"id":1,"first_name":"Mike","last_name":"Hillyer","address_id":3,"picture":null,"email":"Mike.Hillyer@sakilastaff.com","store_id":1,"active":1,"username":"Mike","password":"8cb2237d0679ca88db6464eac60da96345513964","created_at":"2006-02-15T03:57:16.000000Z","updated_at":"2006-02-15T03:57:16.000000Z",
        "address":{"id":3,"address":"23 Workhaven Lane","address2":null,"district":"Alberta","city_id":300,"postal_code":"","phone":"14033335568","location":"0x000000000101000000CDC4196863345CC01DEE7E7099D94840","created_at":"2014-09-25T22:30:27.000000Z","updated_at":"2014-09-25T22:30:27.000000Z",
        "city":{"id":300,"city":"Lethbridge","country_id":20,"created_at":"2006-02-15T04:45:25.000000Z","updated_at":"2006-02-15T04:45:25.000000Z",
        "country":{"id":20,"country":"Canada","created_at":"2006-02-15T04:44:00.000000Z","updated_at":"2006-02-15T04:44:00.000000Z"}}}},

        {"id":2,"first_name":"Jon","last_name":"Stephens","address_id":4,"picture":null,"email":"Jon.Stephens@sakilastaff.com","store_id":2,"active":1,"username":"Jon","password":null,"created_at":"2006-02-15T03:57:16.000000Z","updated_at":"2006-02-15T03:57:16.000000Z",
        "address":{"id":4,"address":"1411 Lillydale Drive","address2":null,"district":"QLD","city_id":576,"postal_code":"","phone":"6172235589","location":"0x0000000001010000005B0DE4341F26634042D6AE6422A23BC0","created_at":"2014-09-25T22:30:09.000000Z","updated_at":"2014-09-25T22:30:09.000000Z",
        "city":{"id":576,"city":"Woodridge","country_id":8,"created_at":"2006-02-15T04:45:25.000000Z","updated_at":"2006-02-15T04:45:25.000000Z",
        "country":{"id":8,"country":"Australia","created_at":"2006-02-15T04:44:00.000000Z","updated_at":"2006-02-15T04:44:00.000000Z"}

        }}}]}]


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

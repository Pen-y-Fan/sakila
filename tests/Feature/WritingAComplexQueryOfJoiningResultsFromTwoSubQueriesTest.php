<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Store;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

/**
 * Writing a complex query of joining results from two sub-queries
 * Based on
 * @link https://www.youtube.com/watch?v=5R0tVpkuQ1M&list=PLkyrdyGDWthC-yd9n8R3CEauJC4sFl-kj&index=6
 */
class WritingAComplexQueryOfJoiningResultsFromTwoSubQueriesTest extends TestCase
{
    /**
     * Write a query to display each store's id, city, country and sales they have made.
     */
    public function testDisplayEachStoreIdCityCountryAndSales(): void
    {

        /*

        SELECT store_details.store_id, store_details.city, payment_details.sales
        FROM (
            SELECT sto.store_id, city.city, cont.`country`
            FROM store AS sto
            LEFT JOIN address addr
                ON sto.address_id = addr.address_id
            JOIN city
                ON addr.city_id = city.city_id
            JOIN country cont
                ON city.country_id = cont.country_id
            ) AS store_details
        INNER JOIN (
            select cus.store_id, SUM(pay.`amount`) AS sales
            FROM customer AS cus
            JOIN payment AS pay
                ON cus.customer_id = pay.customer_id
            GROUP BY cus.store_id
            ) AS payment_details
        ON store_details.store_id = payment_details.store_id
        ORDER BY store_details.store_id
        */

        // First query converted:
        $storeDetailsQuery = Store::with(
            [
                'address:id,address,district,city_id',
                'address.city:id,city,country_id',
                'address.city.country:id,country',
            ]
        );

        $storeDetails = $storeDetailsQuery->get();

        Log::info('Store details', [$storeDetails]);

        /*
            [2021-11-28 17:54:11] testing.INFO: Store details
            [{"Illuminate\\Database\\Eloquent\\Collection":[

        {"id":1,"manager_staff_id":1,"address_id":1,"created_at":"2006-02-15T04:57:12.000000Z","updated_at":"2006-02-15T04:57:12.000000Z",
            "address":{"id":1,"address":"47 MySakila Drive","district":"Alberta","city_id":300,
            "city":{"id":300,"city":"Lethbridge","country_id":20,
            "country":{"id":20,"country":"Canada"}}}},

        {"id":2,"manager_staff_id":2,"address_id":2,"created_at":"2006-02-15T04:57:12.000000Z","updated_at":"2006-02-15T04:57:12.000000Z",
            "address":{"id":2,"address":"28 MySQL Boulevard","district":"QLD","city_id":576,
            "city":{"id":576,"city":"Woodridge","country_id":8,
            "country":{"id":8,"country":"Australia"}}}}
        ]}]
        */

        self::assertCount(2, $storeDetails);

        $storeOne = $storeDetails->first();

        $this->assertSame('47 MySakila Drive', $storeOne->address->address);
        $this->assertSame('Alberta', $storeOne->address->district);
        $this->assertSame('Lethbridge', $storeOne->address->city->city);
        $this->assertSame('Canada', $storeOne->address->city->country->country);

        $storeTwo = $storeDetails->find(2);

        $this->assertSame('28 MySQL Boulevard', $storeTwo->address->address);
        $this->assertSame('QLD', $storeTwo->address->district);
        $this->assertSame('Woodridge', $storeTwo->address->city->city);
        $this->assertSame('Australia', $storeTwo->address->city->country->country);


        // $storeDetails = DB::query()
        //     ->select(['sto.store_id', 'city.city', 'cont.country'])
        //     ->from('store AS sto')
        //     ->leftJoin('address AS addr', 'sto.address_id', '=', 'addr.address_id')
        //     ->leftJoin('city', 'addr.city_id', '=', 'city.city_id')
        //     ->join('country AS cont', 'city.country_id', '=', 'cont.country_id');


        self::markTestSkipped('to be converted to model');

        // TODO: Convert this query, possibly withSum <https://laravel.com/docs/8.x/eloquent-relationships#other-aggregate-functions>

        $paymentDetails = DB::query()
            ->select(['cus.store_id', DB::raw('SUM(pay.`amount`) AS sales')])
            ->from('customer AS cus')
            ->join('payment AS pay', 'cus.customer_id', '=', 'pay.customer_id')
            ->groupBy('cus.store_id');

        $resuts = DB::query()
            ->select(['store_details.*', 'payment_details.sales'])
            ->fromSub($storeDetailsQuery, 'store_details')
            ->joinSub($paymentDetails, 'payment_details', 'store_details.store_id', '=', 'payment_details.store_id')
            ->get();


        self::assertCount(2, $paymentDetails->get());
        Log::info('Payment details', [$paymentDetails->get()]);

        /*
        [2021-11-06 12:27:00] testing.INFO: Payment details
        [{"Illuminate\\Support\\Collection":[
        {"store_id":1,"sales":"37001.52"},
        {"store_id":2,"sales":"30414.99"}]}]
        */

        self::assertCount(2, $resuts);
        Log::info('Store with payments results', [$resuts]);

        /*
                [2021-11-06 12:40:28] testing.INFO: Store with payments results
        [{"Illuminate\\Support\\Collection":[
        {"store_id":1,"city":"Lethbridge","country":"Canada","sales":"37001.52"},
        {"store_id":2,"city":"Woodridge","country":"Australia","sales":"30414.99"}
        ]}]
        */
    }
}

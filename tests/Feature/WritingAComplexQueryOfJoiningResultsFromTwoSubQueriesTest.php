<?php

declare(strict_types=1);

namespace Tests\Feature;

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
        self::markTestSkipped('to be converted to model');
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

        $storeDetails = DB::query()
            ->select(['sto.store_id', 'city.city', 'cont.country'])
            ->from('store AS sto')
            ->leftJoin('address AS addr', 'sto.address_id', '=', 'addr.address_id')
            ->leftJoin('city', 'addr.city_id', '=', 'city.city_id')
            ->join('country AS cont', 'city.country_id', '=', 'cont.country_id');

        $paymentDetails = DB::query()
            ->select(['cus.store_id', DB::raw('SUM(pay.`amount`) AS sales')])
            ->from('customer AS cus')
            ->join('payment AS pay', 'cus.customer_id', '=', 'pay.customer_id')
            ->groupBy('cus.store_id');

        $resuts = DB::query()
            ->select(['store_details.*', 'payment_details.sales'])
            ->fromSub($storeDetails, 'store_details')
            ->joinSub($paymentDetails, 'payment_details', 'store_details.store_id', '=', 'payment_details.store_id')
            ->get();

        self::assertCount(2, $storeDetails->get());
        Log::info('Store details', [$storeDetails->get()]);
        /*

                [2021-11-06 12:15:27] testing.INFO: Store details [{"Illuminate\\Support\\Collection":[
        {"store_id":1,"city":"Lethbridge","country":"Canada"},
        {"store_id":2,"city":"Woodridge","country":"Australia"}
        */

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

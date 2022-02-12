<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Store;
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

        $sumByStore = Store::withSum('customerPayments', 'amount')->get();
        $this->assertSame('37001.52', $sumByStore->find(1)->customer_payments_sum_amount);
        $this->assertSame('30414.99', $sumByStore->find(2)->customer_payments_sum_amount);

        $this->assertCount(2, $sumByStore);
        Log::info('Store with payments results', [$sumByStore]);

        /*
            [2021-12-04 00:50:44] testing.INFO: Store with payments results
        [{"Illuminate\\Database\\Eloquent\\Collection":[
        {"id":1,"manager_staff_id":1,"address_id":1,"created_at":"2006-02-15T04:57:12.000000Z","updated_at":"2006-02-15T04:57:12.000000Z","customer_payments_sum_amount":"37001.52"},
        {"id":2,"manager_staff_id":2,"address_id":2,"created_at":"2006-02-15T04:57:12.000000Z","updated_at":"2006-02-15T04:57:12.000000Z","customer_payments_sum_amount":"30414.99"}
        ]}]

        */
    }
}

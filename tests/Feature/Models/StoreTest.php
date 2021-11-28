<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Store;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class StoreTest extends TestCase
{
    public function testTheFirstStoreAddress(): void
    {
        $storeAddress = Store::with('address')
            ->with('address.city')
            ->with('address.city.country')
            ->first();

        Log::info('testTheFirstStoreAddress', [$storeAddress]);

        /*
        [2021-11-27 16:03:09] testing.INFO: testTheFirstStoreAddress [
        {"App\\Models\\Store":{"id":1,"manager_staff_id":1,"address_id":1,"created_at":"2006-02-15T04:57:12.000000Z","updated_at":"2006-02-15T04:57:12.000000Z",
        "address":{"id":1,"address":"47 MySakila Drive","address2":null,"district":"Alberta","city_id":300,"postal_code":"","phone":"","location":"0x0000000001010000003E0A325D63345CC0761FDB8D99D94840","created_at":"2014-09-25T22:30:27.000000Z","updated_at":"2014-09-25T22:30:27.000000Z",
        "city":{"id":300,"city":"Lethbridge","country_id":20,"created_at":"2006-02-15T04:45:25.000000Z","updated_at":"2006-02-15T04:45:25.000000Z",
        "country":{"id":20,"country":"Canada","created_at":"2006-02-15T04:44:00.000000Z","updated_at":"2006-02-15T04:44:00.000000Z"}
        }}}}]
        */

        $this->assertSame(1, $storeAddress->address->id);
        $this->assertSame('47 MySakila Drive', $storeAddress->address->address);
        $this->assertSame('Alberta', $storeAddress->address->district);
        $this->assertSame('Lethbridge', $storeAddress->address->city->city);
        $this->assertSame('Canada', $storeAddress->address->city->country->country);
    }

    public function testTheSecondStoreAddress(): void
    {
        $storeAddress = Store::with('address')
            ->with('address.city')
            ->with('address.city.country')
            ->find(2);

        Log::info('testTheSecondStoreAddress', [$storeAddress]);

        /*
        [2021-11-27 15:58:13] testing.INFO: testTheSecondStoreAddress
        [{"App\\Models\\Store":
        {"id":2,"manager_staff_id":2,"address_id":2,"created_at":"2006-02-15T04:57:12.000000Z","updated_at":"2006-02-15T04:57:12.000000Z",
        "address":{"id":2,"address":"28 MySQL Boulevard","address2":null,"district":"QLD","city_id":576,"postal_code":"","phone":"","location":"0x0000000001010000008E10D4DF812463404EE08C5022A23BC0","created_at":"2014-09-25T22:30:09.000000Z","updated_at":"2014-09-25T22:30:09.000000Z",
        "city":{"id":576,"city":"Woodridge","country_id":8,"created_at":"2006-02-15T04:45:25.000000Z","updated_at":"2006-02-15T04:45:25.000000Z",
        "country":{"id":8,"country":"Australia","created_at":"2006-02-15T04:44:00.000000Z","updated_at":"2006-02-15T04:44:00.000000Z"}
        }}}}]
        */
        $this->assertSame('28 MySQL Boulevard', $storeAddress->address->address);
        $this->assertSame('QLD', $storeAddress->address->district);
        $this->assertSame('Woodridge', $storeAddress->address->city->city);
        $this->assertSame('Australia', $storeAddress->address->city->country->country);
    }

    public function testTheFirstStoreHasManagerStaffOne(): void
    {
        $storeManager = Store::with('staff')
            ->first();

        Log::info('testTheFirstStoreHasManagerStaffOne', [$storeManager]);

        /*
            [2021-11-27 19:14:55] testing.INFO: testTheFirstStoreHasManagerStaffOne
        [{"App\\Models\\Store":{"id":1,"manager_staff_id":1,"address_id":1,"created_at":"2006-02-15T04:57:12.000000Z","updated_at":"2006-02-15T04:57:12.000000Z",
        "staff":{"id":1,"first_name":"Mike","last_name":"Hillyer","address_id":3,"picture":null,"email":"Mike.Hillyer@sakilastaff.com","store_id":1,"active":1,"username":"Mike","password":"8cb2237d0679ca88db6464eac60da96345513964","created_at":"2006-02-15T03:57:16.000000Z","updated_at":"2006-02-15T03:57:16.000000Z"}
        }}]
        */

        $this->assertSame(1, $storeManager->staff->id);
        $this->assertSame('Mike', $storeManager->staff->first_name);
    }

    public function testTheSecondStoreHasManagerStaffTwo(): void
    {
        $storeManager = Store::with('staff')
            ->find(2);

        Log::info('testTheSecondStoreHasManagerStaffTwo', [$storeManager]);

        /*
            [2021-11-27 19:16:41] testing.INFO: testTheSecondStoreHasManagerStaffTwo
        [{"App\\Models\\Store":{"id":2,"manager_staff_id":2,"address_id":2,"created_at":"2006-02-15T04:57:12.000000Z","updated_at":"2006-02-15T04:57:12.000000Z",
        "staff":{"id":2,"first_name":"Jon","last_name":"Stephens","address_id":4,"picture":null,"email":"Jon.Stephens@sakilastaff.com","store_id":2,"active":1,"username":"Jon","password":null,"created_at":"2006-02-15T03:57:16.000000Z","updated_at":"2006-02-15T03:57:16.000000Z"}
        }}]
        */

        $this->assertSame(2, $storeManager->staff->id);
        $this->assertSame('Jon', $storeManager->staff->first_name);
    }
}

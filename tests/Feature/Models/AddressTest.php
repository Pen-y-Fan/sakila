<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Address;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class AddressTest extends TestCase
{
    public function testTheFirstAddressIs47MySakilaDrive(): void
    {
        $firstAddress = Address::first();

        $this->assertSame('47 MySakila Drive', $firstAddress->address);
    }

    public function test47MySakilaDriveIsInLethbridge(): void
    {
        $firstAddress = Address::with('city')->first();

        $city = $firstAddress->city;
        $this->assertSame('Lethbridge', $city->city);
    }

    public function testTheLastAddressIs1325FukuyamaStreet(): void
    {
        $lastAddress = Address::find(605);

        $this->assertSame('1325 Fukuyama Street', $lastAddress->address);
    }

    public function test1325FukuyamaStreetIsInTieli(): void
    {
        $lastAddress = Address::with('city')->orderBy('id', 'DESC')->first();

        $this->assertSame('Tieli', $lastAddress->city->city);
    }

    public function test661ChisinauLaneIsAddressThreeHundred(): void
    {
        $chisinauLane = Address::with('city')->with('city.country')->find(300);

        Log::info('test661ChisinauLaneIsAddressThreeHundred', [$chisinauLane]);
        /*
        [2021-11-27 11:39:45] testing.INFO: test661ChisinauLaneIsAddressThreeHundred
        [{"App\\Models\\Address":
        {
        "id":300,"address":"661 Chisinau Lane","address2":"","district":"Pietari","city_id":274,"postal_code":"8856","phone":"816436065431",
            "location":"0x00000000010100000000000000000000000000000000000000","created_at":"2014-09-25T22:32:51.000000Z","updated_at":"2014-09-25T22:32:51.000000Z",
        "city":{"id":274,"city":"Kolpino","country_id":80,"created_at":"2006-02-15T04:45:25.000000Z","updated_at":"2006-02-15T04:45:25.000000Z",
        "country":{"id":80,"country":"Russian Federation","created_at":"2006-02-15T04:44:00.000000Z","updated_at":"2006-02-15T04:44:00.000000Z"}
        }}}]
        */

        $this->assertSame('661 Chisinau Lane', $chisinauLane->address);
        $this->assertSame('Pietari', $chisinauLane->district);
        $this->assertSame('Kolpino', $chisinauLane->city->city);
        $this->assertSame('Russian Federation', $chisinauLane->city->country->country);
    }

    public function testTheFirstAddressIsStoreOne(): void
    {
        $firstAddress = Address::with('store')->first();

        $this->assertSame(1, $firstAddress->store->id);
    }

    public function testTheThirdAddressIsStaffOne(): void
    {
        $staffAddress = Address::with('staff')->find(3);

        $this->assertSame(1, $staffAddress->staff->first()->id);
        $this->assertSame('Mike', $staffAddress->staff->first()->first_name);
    }

    public function testTheForthAddressIsStaffTwo(): void
    {
        $staffAddress = Address::with('staff')->find(4);

        $this->assertSame(2, $staffAddress->staff->first()->id);
        $this->assertSame('Jon', $staffAddress->staff->first()->first_name);
    }

    public function testTheThirdAddressIsStaffOneWhoWorksAtAddressOne(): void
    {
        $staffAddress = Address::with('staff')
            ->with('staff.store')
            ->with('staff.store.address')
            ->find(3);

        Log::info('testTheThirdAddressIsStaffOneWhoWorksAtAddressOne', [$staffAddress]);

        /*
        [2021-11-27 19:01:55] testing.INFO: testTheThirdAddressIsStaffOneWhoWorksAtAddressOne [
        {"App\\Models\\Address":{"id":3,"address":"23 Workhaven Lane","address2":null,"district":"Alberta","city_id":300,"postal_code":"","phone":"14033335568","location":"0x000000000101000000CDC4196863345CC01DEE7E7099D94840","created_at":"2014-09-25T22:30:27.000000Z","updated_at":"2014-09-25T22:30:27.000000Z",
        "staff":[
            {"id":1,"first_name":"Mike","last_name":"Hillyer","address_id":3,"picture":null,"email":"Mike.Hillyer@sakilastaff.com","store_id":1,"active":1,"username":"Mike","password":"8cb2237d0679ca88db6464eac60da96345513964","created_at":"2006-02-15T03:57:16.000000Z","updated_at":"2006-02-15T03:57:16.000000Z",
            "store":{"id":1,"manager_staff_id":1,"address_id":1,"created_at":"2006-02-15T04:57:12.000000Z","updated_at":"2006-02-15T04:57:12.000000Z",
            "address":{"id":1,"address":"47 MySakila Drive","address2":null,"district":"Alberta","city_id":300,"postal_code":"","phone":"","location":"0x0000000001010000003E0A325D63345CC0761FDB8D99D94840","created_at":"2014-09-25T22:30:27.000000Z","updated_at":"2014-09-25T22:30:27.000000Z"}
        }}
        ]}}]
        */

        $this->assertSame('47 MySakila Drive', $staffAddress->staff->first()->store->address->address);
    }

    public function testTheFifthAddressIsCustomerMarySmith(): void
    {
        $customerAddress = Address::with(
            [
                'customer:first_name,last_name,address_id',
            ]
        )->find(5);

        Log::info('testTheFifthAddressIsCustomerMarySmith', [$customerAddress]);

        /*
            [2021-11-30 22:51:42] testing.INFO: testTheFifthAddressIsCustomerMarySmith [
        {"App\\Models\\Address":{"id":5,"address":"1913 Hanoi Way","address2":"","district":"Nagasaki","city_id":463,"postal_code":"35200","phone":"28303384290","location":"0x00000000010100000028D1370E21376040ABB58BC45F944040","created_at":"2014-09-25T22:31:53.000000Z","updated_at":"2014-09-25T22:31:53.000000Z",
        "customer":{"first_name":"MARY","last_name":"SMITH","address_id":5}
        }}]
        */

        $this->assertSame('MARY', $customerAddress->customer->first_name);
        $this->assertSame('SMITH', $customerAddress->customer->last_name);
    }
}

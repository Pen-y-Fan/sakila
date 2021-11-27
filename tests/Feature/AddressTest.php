<?php

declare(strict_types=1);

namespace Tests\Feature;

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


}

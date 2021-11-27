<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\City;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class CityTest extends TestCase
{
    public function testTheFirstCityIsLaCorua(): void
    {
        $firstCity = City::first();

        $this->assertSame('A Corua (La Corua)', $firstCity->city);
    }

    public function testLaCoruaIsInSpain(): void
    {
        $firstCity = City::with('country')->find(1);

        $country = $firstCity->country;
        $this->assertSame('Spain', $country->country);
    }

    public function testTheLastCityIsZiguinchor(): void
    {
        $lastCity = City::find(600);

        $this->assertSame('Ziguinchor', $lastCity->city);
    }

    public function testZiguinchorIsInSenegal(): void
    {
        $lastCity = City::with('country')->find(600);

        $this->assertSame('Senegal', $lastCity->country->country);
    }

    public function testLethbridgeIsCitySixHundred(): void
    {
        $lethbridge = City::find(300);

        $this->assertSame('Lethbridge', $lethbridge->city);
    }

    public function testLethbridgeHasTwoAddresses(): void
    {
        $lethbridge = City::with('addresses')->find(300);

        Log::info('testLethbridgeHasTwoAddresses', [$lethbridge]);

        /*
            [2021-11-27 11:51:02] testing.INFO: testLethbridgeHasTwoAddresses [{
"           App\\Models\\City":
        {"id":300,"city":"Lethbridge","country_id":20,"created_at":"2006-02-15T04:45:25.000000Z","updated_at":"2006-02-15T04:45:25.000000Z",
        "addresses":[
        {"id":1,"address":"47 MySakila Drive","address2":null,"district":"Alberta","city_id":300,"postal_code":"","phone":"","location":"0x0000000001010000003E0A325D63345CC0761FDB8D99D94840","created_at":"2014-09-25T22:30:27.000000Z","updated_at":"2014-09-25T22:30:27.000000Z"},
        {"id":3,"address":"23 Workhaven Lane","address2":null,"district":"Alberta","city_id":300,"postal_code":"","phone":"14033335568","location":"0x000000000101000000CDC4196863345CC01DEE7E7099D94840","created_at":"2014-09-25T22:30:27.000000Z","updated_at":"2014-09-25T22:30:27.000000Z"}
        ]}}]
        */

        $this->assertSame(2, $lethbridge->addresses->count());
        $this->assertSame('47 MySakila Drive', $lethbridge->addresses->first()->address);
        $this->assertSame('23 Workhaven Lane', $lethbridge->addresses->last()->address);
    }




}

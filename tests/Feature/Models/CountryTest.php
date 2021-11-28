<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Country;
use Tests\TestCase;

class CountryTest extends TestCase
{
    public function testTheFirstCountryIsAfghanistan(): void
    {
        $firstCountry = Country::first();

        $this->assertSame('Afghanistan', $firstCountry->country);
    }

    public function testAfghanistanHasCityOfKabul(): void
    {
        $countries = Country::where('country', '=', 'Afghanistan')->with('cities')->first();

        $cities = $countries->cities->first();
        $this->assertSame('Kabul', $cities->city);
    }

    public function testTheLastCountryIsZambia(): void
    {
        $lastCountry = Country::find(109);

        $this->assertSame('Zambia', $lastCountry->country);
    }

    public function testZambiaHasACityCalledKitwe(): void
    {
        $lastCountry = Country::with('cities')->find(109);

        $cities = $lastCountry->cities->first();
        $this->assertSame('Kitwe', $cities->city);
    }

    public function testJapanIsCountryFifty(): void
    {
        $fiftiethCountry = Country::where('country', 'Japan')->first();

        $this->assertSame(50, $fiftiethCountry->id);
    }

    public function testJapanHasThirtyOneCities(): void
    {
        $japan = Country::with('cities')->find(50);

        $count = $japan->cities->count();
        $this->assertSame(31, $count);
    }
}

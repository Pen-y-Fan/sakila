<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\City;
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

    public function testZiguinchorIsIn(): void
    {
        $lastCity = City::with('country')->find(600);

        $this->assertSame('Senegal', $lastCity->country->country);
    }

    public function testLethbridgeIsCitySixHundred(): void
    {
        $lethbridge = City::find(300);

        $this->assertSame('Lethbridge', $lethbridge->city);
    }

}

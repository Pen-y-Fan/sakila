<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Category;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    public function testTheFirstCategoryIsAction(): void
    {
        $firstCategory = Category::first();

        $this->assertSame('Action', $firstCategory->name);
    }

    public function testTheLastCategoryIsTravel(): void
    {
        $lastCategory = Category::find(16);

        $this->assertSame('Travel', $lastCategory->name);
    }

    public function testActionHasSixtyFourFilms(): void
    {
        $firstCategory = Category::withCount('films')->first();

        $this->assertSame(64, $firstCategory->films_count);
    }

    public function testActionHasAmadeusHolyAndWorstBanger(): void
    {
        $firstCategory = Category::with('films:title')->first();

        $this->assertSame('AMADEUS HOLY', $firstCategory->films->first()->title);
        $this->assertSame('WORST BANGER', $firstCategory->films->last()->title);
    }
}

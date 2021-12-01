<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Inventory;
use Tests\TestCase;

class InventoryTest extends TestCase
{
    const FIRST = [
        "id"         => 1,
        "film_id"    => 1,
        "store_id"   => 1,
        "created_at" => "2006-02-15 05:09:17",
        "updated_at" => "2006-02-15 05:09:17",
    ];

    const LAST = [
        "id"         => 1000,
        "film_id"    => 223,
        "store_id"   => 2,
        "created_at" => "2006-02-15 05:09:17",
        "updated_at" => "2006-02-15 05:09:17",
    ];

    public function testThereAreOneThousandItemsOfInventory(): void
    {
        $inventoryCount = Inventory::count();

        $this->assertSame(1000, $inventoryCount);
    }

    public function testTheFirstInventoryItemIsFilmAcademyDinosaur(): void
    {
        $firstInventory = Inventory::with('film')->first();

        $this->assertSame('ACADEMY DINOSAUR', $firstInventory->film->title);
    }

    public function testTheFirstInventoryItemIsStore(): void
    {
        $firstInventory = Inventory::with('store')->first();

        $this->assertSame(self::FIRST['store_id'], $firstInventory->store->id);
        $this->assertSame('47 MySakila Drive', $firstInventory->store->address->address);
    }

    public function testTheLastInventoryItemIsFilmDesireAlien(): void
    {
        $firstInventory = Inventory::with('film')->find(self::LAST['id']);

        $this->assertSame('DESIRE ALIEN', $firstInventory->film->title);
    }

    // The first inventory was rented on....
}

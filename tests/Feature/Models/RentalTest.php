<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Rental;
use Tests\TestCase;

class RentalTest extends TestCase
{
    public const FIRST = [1, '2005-05-24 22:53:30', 367, 130, '2005-05-26 22:04:30', 1, '2006-02-15 21:30:53'];

    public const EIGHT_K = [8000, '2005-07-28 15:10:25', 1644, 212, '2005-08-06 19:15:25', 1, '2006-02-15 21:30:53'];

    public const LAST = [16049, '2005-08-23 22:50:12', 2666, 393, '2005-08-30 01:01:12', 2, '2006-02-15 21:30:53'];

    public function testTheFirstRentalIsMigrated(): void
    {
        $firstRental = $this->getRental(self::FIRST);
        $rental      = Rental::first();

        $this->assertSame($firstRental['id'], $rental->id);
        $this->assertSame($firstRental['rental_date'], $rental->rental_date);
        $this->assertSame($firstRental['customer_id'], $rental->customer_id);
    }

    public function testTheLastRentalIsMigrated(): void
    {
        $lastRental = $this->getRental(self::LAST);
        $rental     = Rental::find($lastRental['id']);

        $this->assertSame($lastRental['id'], $rental->id);
        $this->assertSame($lastRental['rental_date'], $rental->rental_date);
        $this->assertSame($lastRental['customer_id'], $rental->customer_id);
    }

    public function testThe8000RentalIsMigrated(): void
    {
        $rental8000 = $this->getRental(self::EIGHT_K);
        $rental     = Rental::find($rental8000['id']);

        $this->assertSame($rental8000['id'], $rental->id);
        $this->assertSame($rental8000['rental_date'], $rental->rental_date);
        $this->assertSame($rental8000['customer_id'], $rental->customer_id);
    }

    public function testTheFirstRentalIsForCustomer130(): void
    {
        $rental = Rental::with('customer')->first();

        $this->assertSame('CHARLOTTE', $rental->customer->first_name);
        $this->assertSame('HUNTER', $rental->customer->last_name);
    }

    public function testTheFirstRentalIsForStaff1(): void
    {
        $rental = Rental::with('staff')->first();

        $this->assertSame('Mike', $rental->staff->first_name);
        $this->assertSame('Hillyer', $rental->staff->last_name);
    }

    public function testTheFirstRentalIsForInventory367(): void
    {
        $rental = Rental::with('inventory')->first();

        $this->assertSame(367, $rental->inventory->id);
        $this->assertSame('BLANKET BEVERLY', $rental->inventory->film->title);
    }

    public function testTheFirstRentalPaymentIs299(): void
    {
        $rental = Rental::with('payments')->first();

        $this->assertSame(3504, $rental->payments->first()->id);
        $this->assertSame('2.99', $rental->payments->first()->amount);
    }

    public function testTheFirstRentalPaymentWasForFilmBlanketBeverly(): void
    {
        $rental = Rental::with('film')->first();

        $this->assertSame(80, $rental->film->id);
        $this->assertSame('BLANKET BEVERLY', $rental->film->title);
    }

    public function testTheFirstRentalPaymentWasFromStore1(): void
    {
        $rental = Rental::with('store')->first();

        $this->assertSame(1, $rental->store->id);
        $this->assertSame('Alberta', $rental->store->address->district);
    }

    private function getRental($record): array
    {
        return [
            'id'           => $record[0],
            'rental_date'  => $record[1],
            'inventory_id' => $record[2],
            'customer_id'  => $record[3],
            'return_date'  => $record[4],
            'staff_id'     => $record[5],
            'created_at'   => $record[6],
            'updated_at'   => $record[6],
        ];
    }
}

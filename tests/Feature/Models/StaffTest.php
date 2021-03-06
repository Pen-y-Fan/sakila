<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Staff;
use Tests\TestCase;

class StaffTest extends TestCase
{
    public function testTheFirstStaffIsMikeHillyer(): void
    {
        $firstStaff = Staff::first();

        $this->assertSame('Mike', $firstStaff->first_name);
        $this->assertSame('Hillyer', $firstStaff->last_name);
        $this->assertSame('Hillyer', $firstStaff->last_name);
        $this->assertSame('Mike.Hillyer@sakilastaff.com', $firstStaff->email);
    }

    public function testTheFirstStaffIsJonStephens(): void
    {
        $staff = Staff::find(2);

        $this->assertSame('Jon', $staff->first_name);
        $this->assertSame('Stephens', $staff->last_name);
        $this->assertSame('Jon.Stephens@sakilastaff.com', $staff->email);
    }

    public function testMikeHillyerAddressIs23WorkhavenLane(): void
    {
        $firstStaff = Staff::with('address')->first();

        $this->assertSame('23 Workhaven Lane', $firstStaff->address->address);
    }

    public function testJonStephensAddressIs1411LillydaleDrive(): void
    {
        $jonStephens = Staff::with('address')->find(2);

        $this->assertSame('1411 Lillydale Drive', $jonStephens->address->address);
    }

    public function testMikeHillyerStoreAddressIs47MySakilaDrive(): void
    {
        $mikeHillyer = Staff::with('store')->with('store.address')->first();

        $this->assertSame(1, $mikeHillyer->store->id);
        $this->assertSame('47 MySakila Drive', $mikeHillyer->store->address->address);
    }

    public function testJonStephensStoreAddressIs28MySqlBoulevard(): void
    {
        $jonStephens = Staff::with('store')->with('store.address')->find(2);

        $this->assertSame(2, $jonStephens->store->id);
        $this->assertSame('28 MySQL Boulevard', $jonStephens->store->address->address);
    }

    public function testJonStephensFirstRentalWasInventory2452Film535LoveSuicides(): void
    {
        $jonStephens = Staff::with('rentals')->find(2);

        $this->assertSame(2452, $jonStephens->rentals->first()->inventory_id);
        $this->assertSame('LOVE SUICIDES', $jonStephens->rentals->first()->inventory->film->title);
    }

    public function testMikeHillyerFirstPaymentTakenWas299(): void
    {
        $mikeHillyer = Staff::with('payments')->first();

        $this->assertSame(1, $mikeHillyer->payments->first()->id);
        $this->assertSame('2.99', $mikeHillyer->payments->first()->amount);
    }

    public function testMikeHillyerTakingsForJune2005(): void
    {
        $mikeHillyer = Staff::with('payments')
            ->join('payments', 'staff.id', '=', 'payments.staff_id')
            ->whereBetween('payments.payment_date', ['2005-06-01 00:00:00', '2005-06-30 23:59:59'])->get();

        info('MikeHillyerFirstPaymentForJune2005', [$mikeHillyer]);

        $this->assertEqualsWithDelta(9631.88, $mikeHillyer->sum->amount, 0.01);
        $this->assertSame(2312, $mikeHillyer->count());
    }
}

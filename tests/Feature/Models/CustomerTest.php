<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Customer;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    public function testThereAre599Customers(): void
    {
        $customerCount = Customer::count();

        $this->assertSame(599, $customerCount);
    }

    public function testTheFirstCustomerIsMarySmith(): void
    {
        $firstCustomer = Customer::first();

        $customer = [
            'id'         => 1,
            'store_id'   => 1,
            'first_name' => 'MARY',
            'last_name'  => 'SMITH',
            'email'      => 'MARY.SMITH@sakilacustomer.org',
            'address_id' => 5,
            'active'     => 1,
            'created_at' => '2006-02-14 22:04:36',
            'updated_at' => '2006-02-15 04:57:20',
        ];

        $this->assertSame($customer['first_name'], $firstCustomer->first_name);
        $this->assertSame($customer['last_name'], $firstCustomer->last_name);
        $this->assertSame($customer['email'], $firstCustomer->email);
    }

    public function testTheLastCustomerStaffIsAustinCintron(): void
    {
        $customer599 = Customer::find(599);

        $customer = [
            'id'         => 599,
            'store_id'   => 2,
            'first_name' => 'AUSTIN',
            'last_name'  => 'CINTRON',
            'email'      => 'AUSTIN.CINTRON@sakilacustomer.org',
            'address_id' => 605,
            'active'     => 1,
            'created_at' => '2006-02-14 22:04:37',
            'updated_at' => '2006-02-15 04:57:20',
        ];

        $this->assertSame($customer['first_name'], $customer599->first_name);
        $this->assertSame($customer['last_name'], $customer599->last_name);
        $this->assertSame($customer['email'], $customer599->email);
    }

    public function testMarySmithAddressIs1913HanoiWay(): void
    {
        $firstCustomer = Customer::with('address')->first();

        $this->assertSame('1913 Hanoi Way', $firstCustomer->address->address);
    }

    public function testMarySmithStoreAddressIs47MySakilaDrive(): void
    {
        $firstCustomer = Customer::with('store')->with('store.address')->first();

        $this->assertSame(1, $firstCustomer->store->id);
        $this->assertSame('47 MySakila Drive', $firstCustomer->store->address->address);
    }

    public function testActiveCustomersCountStoreOne(): void
    {
        $activeCustomers = Customer::where('active', 1)->where('store_id', 1)->count();

        Log::info('testActiveCustomersCountByStore', [$activeCustomers]);

        $this->assertSame(318, $activeCustomers);
    }

    public function testCustomerOneFirstRentalIsFilmPatientSister(): void
    {
        $customerOne = Customer::with('rentals')->first();

        $this->assertSame(76, $customerOne->rentals->first()->id);
        $this->assertSame('PATIENT SISTER', $customerOne->rentals->first()->inventory->film->title);
    }

    public function testCustomerOneFirstPaymentIs299(): void
    {
        $customerOne = Customer::with('payments')->first();

        $this->assertSame(1, $customerOne->payments->first()->id);
        $this->assertSame('2.99', $customerOne->payments->first()->amount);
    }
}

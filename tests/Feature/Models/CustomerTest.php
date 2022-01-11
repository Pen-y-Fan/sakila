<?php
/** @noinspection PhpArrayWriteIsNotUsedInspection */

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Country;
use App\Models\Customer;
use App\Models\Payment;
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

    /** @noinspection SpellCheckingInspection */
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

    /** @noinspection SpellCheckingInspection */
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

    public function testAustralianCustomerTotalSpend(): void
    {
        // real world example, if we wanted to search for customers in Australia.
        $search = 'Australia';
        $country = Country::whereCountry($search)->first();

        // Note: this is the customers store address in Australia, not the customers address!
        // this gives us all the customers who have the Australian store and their spend
        $australianCustomers = Customer::with('payments')
            ->whereHas(
                'store.address.city.country',
                fn($query) => $query->where('country', $country->country)
            )
            ->whereHas(
                'payments',
                fn($query) => $query->where('amount', '>', 0)
            )
            ->get();
        // Maybe we would loop over all the customers and display their spend 🤷

        // Just an example on how to total the collection using PHP, see
        // WritingAComplexQueryOfJoiningResultsFromTwoSubQueriesTest for an example using withSum

        $totalSpend = $australianCustomers->sum(
            fn($customer) => $customer->payments->sum('amount')
        );

        $this->assertSame(30414.99, $totalSpend);
    }

    public function testTheLastPaymentOffEveryCustomer(): void
    {
        $customer = Customer::addSelect([
            'last_payment' => Payment::select('amount')
                ->whereColumn('customer_id', 'customers.id')
                ->orderByDesc('payment_date')
                ->limit(1),
        ])->get();

        $this->assertSame(5.99, (float)$customer->first()->last_payment);

        /*
            0 => array:10 [
            "id" => 1
            "store_id" => 1
            "first_name" => "MARY"
            "last_name" => "SMITH"
            "email" => "MARY.SMITH@sakilacustomer.org"
            "address_id" => 5
            "active" => true
            "created_at" => "2006-02-14T22:04:36.000000Z"
            "updated_at" => "2006-02-15T04:57:20.000000Z"
            "last_payment" => "5.99"
            ]

        // etc....
        */
    }

    public function testTheLastTenPayingCustomers(): void
    {
        /** @var \Illuminate\Database\Eloquent\Collection<Customer> $lastTenPayingCustomer
         * @noinspection PhpFullyQualifiedNameUsageInspection
         */
        $lastTenPayingCustomer = Customer::select(['id', 'first_name', 'last_name'])
            ->orderByDesc(
                Payment::select('payment_date')
                    ->whereColumn('customer_id', 'customers.id')
                    ->orderByDesc('payment_date')
                    ->limit(1)
            )
            ->limit(10)
            ->get();

        $expected = [
            'id'         => 14,
            'first_name' => 'BETTY',
            'last_name'  => 'WHITE',
        ];

        $this->assertSame($expected, $lastTenPayingCustomer->first()->toArray());
    }

    public function testTheTopTenPayingCustomers(): void
    {
        /** @var \Illuminate\Database\Eloquent\Collection<Customer> $topTenPayingCustomers
         * @noinspection PhpFullyQualifiedNameUsageInspection
         */
        $topTenPayingCustomers = Customer::addSelect([
            'total_payments' => Payment::selectRaw('SUM(amount)')
                ->whereColumn('customer_id', 'customers.id'),
        ])
            ->orderByDesc('total_payments')
            ->limit(10)
            ->get();

        $expectedFirst = [
            "id"             => 526,
            "store_id"       => 2,
            "first_name"     => "KARL",
            "last_name"      => "SEAL",
            "email"          => "KARL.SEAL@sakilacustomer.org",
            "address_id"     => 532,
            "active"         => true,
            "created_at"     => "2006-02-14T22:04:37.000000Z",
            "updated_at"     => "2006-02-15T04:57:20.000000Z",
            "total_payments" => "221.55",
        ];

        $this->assertCount(10, $topTenPayingCustomers);
        $this->assertSame($expectedFirst, $topTenPayingCustomers->first()->toArray());
        $this->assertEqualsWithDelta(
            1912.96,
            $topTenPayingCustomers->sum('total_payments'),
            0.01,
            'Top 10 customers total spend should be 1912.96'
        );
    }

    public function testTotalSpend(): void
    {
        /** @var \Illuminate\Database\Eloquent\Collection<Customer> $topTenPayingCustomers
         * @noinspection PhpFullyQualifiedNameUsageInspection
         */
        $topTenPayingCustomers = Customer::select(['id', 'first_name', 'last_name'])
            ->addSelect([
                'total_payments' => Payment::selectRaw('SUM(amount)')
                    ->whereColumn('customer_id', 'customers.id'),
            ])
            ->orderByDesc('total_payments')
            ->get();

        $expectedFirst = [
            "id"             => 526,
            "first_name"     => "KARL",
            "last_name"      => "SEAL",
            "total_payments" => "221.55",
        ];

        $this->assertCount(599, $topTenPayingCustomers);
        $this->assertSame($expectedFirst, $topTenPayingCustomers->first()->toArray());
        $this->assertEqualsWithDelta(
            67416.51,
            $topTenPayingCustomers->sum('total_payments'),
            0.01,
            'total spend should be 67416.51'
        );
    }
}

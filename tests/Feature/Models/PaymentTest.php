<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    public const FIRST = [1, 1, 1, 76, 2.99, '2005-05-25 11:30:37', '2006-02-15 22:12:30'];

    public const EIGHT_K = [8000, 295, 1, 8108, 7.99, '2005-07-28 19:07:38', '2006-02-15 22:15:36'];

    public const LAST = [16049, 599, 2, 15725, 2.99, '2005-08-23 11:25:00', '2006-02-15 22:24:13'];

    public function testThereAre16049Payments(): void
    {
        $paymentCount = Payment::count();

        $this->assertSame($this->getPayment(self::LAST)['id'], $paymentCount);
    }

    public function testTheFirstPaymentIsMigrated(): void
    {
        $firstPayment = $this->getPayment(self::FIRST);
        $payment = Payment::first();

        $this->assertSame($firstPayment['id'], $payment->id);
        $this->assertSame($firstPayment['payment_date'], $payment->payment_date);
        $this->assertSame($firstPayment['customer_id'], $payment->customer_id);
    }

    public function testTheLastPaymentIsMigrated(): void
    {
        $lastPayment = $this->getPayment(self::LAST);
        $payment = Payment::find($lastPayment['id']);

        $this->assertSame($lastPayment['id'], $payment->id);
        $this->assertSame($lastPayment['payment_date'], $payment->payment_date);
        $this->assertSame($lastPayment['customer_id'], $payment->customer_id);
    }

    public function testThe8000PaymentIsMigrated(): void
    {
        $payment8000 = $this->getPayment(self::EIGHT_K);

        $payment = Payment::find($payment8000['id']);

        $this->assertSame($payment8000['id'], $payment->id);
        $this->assertSame($payment8000['payment_date'], $payment->payment_date);
        $this->assertSame($payment8000['customer_id'], $payment->customer_id);
    }

    public function testTheFirstPaymentIsForCustomer1MarySmith(): void
    {
        $payment = Payment::with('customer')->first();

        $this->assertSame('MARY', $payment->customer->first_name);
        $this->assertSame('SMITH', $payment->customer->last_name);
    }

    public function testTheFirstPaymentIsForStaff1(): void
    {
        $payment = Payment::with('staff')->first();

        $this->assertSame('Mike', $payment->staff->first_name);
        $this->assertSame('Hillyer', $payment->staff->last_name);
    }

    public function testTheFirstPaymentIsForRental76(): void
    {
        $payment = Payment::with('rental')->first();

        $this->assertSame(76, $payment->rental->id);
        $this->assertSame('2005-06-03 12:00:37', $payment->rental->return_date);
    }

    public function testTheSumOFAllPaymentsIs6741651(): void
    {
        $sum = Payment::sum('amount');

        $this->assertSame('67416.51', $sum);
    }

    public function testTopCustomersPercentageOfSpend(): void
    {
        $totalQuery = Payment::selectRaw('SUM(amount) AS total');

        $payments = Payment::selectRaw('payments.*, (amount / overall.total) * 100 AS percent_of_total')
            ->crossJoinSub($totalQuery, 'overall')
            ->where('amount', '>', 0)
            ->orderBy('amount', 'DESC')
            ->limit(10)
            ->get();

        $expectedTop = [
            'id'               => 342,
            'customer_id'      => 13,
            'staff_id'         => 2,
            'rental_id'        => 8831,
            'amount'           => '11.99',
            'payment_date'     => '2005-07-29 22:37:41',
            'created_at'       => '2006-02-15 22:12:31',
            'updated_at'       => '2006-02-15 22:12:31',
            'total'            => '67416.51',
            'percent_of_total' => '0.017785',
        ];

        $this->assertEqualsWithDelta(
            $expectedTop['percent_of_total'],
            $payments->first()->percent_of_total,
            0.000001,
            'percent_of_total should be 0.017785'
        );
        $this->assertSame($expectedTop['id'], $payments->first()->id);
        $this->assertCount(10, $expectedTop);
    }

    public function testPaymentsInJuly2005(): void
    {
        $sumPaymentsJune05 = Payment::query()
            ->paymentAfter('2005-07-01')
            ->paymentBefore('2005-07-31')
            ->sum('amount');

        $this->assertSame('28373.89', $sumPaymentsJune05);
    }

    /*
     select year(payment_date),month(payment_date),sum(amount)
     from payment
     group by year(payment_date),month(payment_date)
     order by year(payment_date),month(payment_date);
     */

    public function testPaymentsByYearAndMonth(): void
    {
        $summaryOfPaymentsByYearAndMonth = DB::table('payments')
            ->select(
                [
                    DB::raw('year(payment_date) as year'),
                    DB::raw('month(payment_date) as month'),
                    DB::raw('sum(amount) as payments')
                ]
            )
            ->groupByRaw('year,month')
            ->orderByRaw('year,month')
            ->get();

        \Log::info('$summaryOfPaymentsByYearAndMonth', [$summaryOfPaymentsByYearAndMonth]);

        /*
         [{"Illuminate\\Support\\Collection":
        [{"year":2005,"month":5,"payments":"4824.43"},
        {"year":2005,"month":6,"payments":"9631.88"},
        {"year":2005,"month":7,"payments":"28373.89"},
        {"year":2005,"month":8,"payments":"24072.13"},
        {"year":2006,"month":2,"payments":"514.18"}]}]
         */
        $this->assertSame(
            "28373.89",
            $summaryOfPaymentsByYearAndMonth
                ->where('year', '=', '2005')
                ->where('month', '=', '7')
                ->first()
                ->payments
        );
    }

    private function getPayment($record): array
    {
        return [
            'id'           => $record[0],
            'customer_id'  => $record[1],
            'staff_id'     => $record[2],
            'rental_id'    => $record[3],
            'amount'       => $record[4],
            'payment_date' => $record[5],
            'created_at'   => $record[6],
            'updated_at'   => $record[6],
        ];
    }

}

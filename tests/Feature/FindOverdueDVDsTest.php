<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Rental;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

/**
 * Find Overdue DVDs
 * source: <https://dev.mysql.com/doc/sakila/en/sakila-usage.html>
 */
class FindOverdueDVDsTest extends TestCase
{
    /**
     * Display Overdue DVDs
     */
    public function testDisplayOverdueDVDs(): void
    {
        $overdueFilms = Rental::whereNull('return_date')
            ->with(
                [
                    'customer:id,first_name,last_name,address_id',
                    'customer.address:id,phone',
                    'inventory:id,film_id,store_id',
                    'inventory.film:id,title,rental_duration'
                ]
            )
            ->get();

        // would need to filter on the overdue date too!
        $overdueFilms->transform(function($rental) {
            return collect([
                "customer" => $rental->customer->last_name . ', ' . $rental->customer->first_name,
                "phone" => $rental->customer->address->phone,
                "title" => $rental->inventory->film->title
            ]);
        });

        Log::info('Over due films', [$overdueFilms]);

        /*
            [2021-12-02 23:13:09] testing.INFO: Over due films
        [{"Illuminate\\Support\\Collection":[
        {"customer":"KNIGHT, GAIL","phone":"904253967161","title":"HYDE DOCTOR"},
        {"customer":"MAULDIN, GREGORY","phone":"80303246192","title":"HUNGER ROOF"},
        {"customer":"JENKINS, LOUISE","phone":"800716535041","title":"FRISCO FORREST"},
        {"customer":"HOWELL, WILLIE","phone":"991802825778","title":"TITANS JERK"},
        {"customer":"DIAZ, EMILY","phone":"333339908719","title":"CONNECTION MICROCOSMOS"},
        {"customer":"LAWRENCE, LAURIE","phone":"956188728558","title":"HAUNTED ANTITRUST"},
        {"customer":"ANDERSON, LISA","phone":"635297277345","title":"BULL SHAWSHANK"},
        {"customer":"DUGGAN, FREDDIE","phone":"644021380889","title":"GHOST GROUNDHOG"},

        ....
        ]}]
         */

        self::assertCount(183, $overdueFilms);
    }
}

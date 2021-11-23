<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
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
        self::markTestSkipped('to be converted to model');
        /*

        Many DVD stores produce a daily list of overdue rentals so that customers can be contacted and asked to
         return their overdue DVDs.

        To create such a list, search the rental table for films with a return date that is NULL and where the rental
         date is further in the past than the rental duration specified in the film table. If so, the film is overdue
         and we should produce the name of the film along with the customer name and phone number.



        SELECT
            CONCAT(customer.last_name, ', ', customer.first_name) AS customer,
            address.phone,
            film.title
        FROM rental INNER JOIN customer ON rental.customer_id = customer.customer_id
                    INNER JOIN address ON customer.address_id = address.address_id
                    INNER JOIN inventory ON rental.inventory_id = inventory.inventory_id
                    INNER JOIN film ON inventory.film_id = film.film_id
        WHERE rental.return_date IS NULL
          AND rental_date + INTERVAL film.rental_duration DAY < CURRENT_DATE()
        ORDER BY title
        LIMIT 5;

        */

        $overdueFilms = DB::query()
            ->select(
                [
                    DB::raw("CONCAT(customer.last_name, ', ', customer.first_name) AS customer"),
                    'address.phone',
                    'film.title',
                ]
            )
            ->from('rental')
            ->leftJoin('customer', 'rental.customer_id', '=', 'customer.customer_id')
            ->leftJoin('address', 'customer.address_id', '=', 'address.address_id')
            ->leftJoin('inventory', 'rental.inventory_id', '=', 'inventory.inventory_id')
            ->leftJoin('film', 'inventory.film_id', '=', 'film.film_id')
            ->whereNull('rental.return_date')
            ->where(DB::raw('rental_date + INTERVAL film.rental_duration DAY'), '<', 'CURRENT_DATE()')
            ->orderBy('title')
            ->limit(5);

        Log::info('Overdue films SQL', [$overdueFilms->toSQL()]);

        /*

        SELECT
           CONCAT(CUSTOMER.LAST_NAME, ', ', CUSTOMER.FIRST_NAME) AS CUSTOMER,
           `ADDRESS`.`PHONE`,
           `FILM`.`TITLE`
        FROM
           `RENTAL`
           LEFT JOIN
              `CUSTOMER`
              ON `RENTAL`.`CUSTOMER_ID` = `CUSTOMER`.`CUSTOMER_ID`
           LEFT JOIN
              `ADDRESS`
              ON `CUSTOMER`.`ADDRESS_ID` = `ADDRESS`.`ADDRESS_ID`
           LEFT JOIN
              `INVENTORY`
              ON `RENTAL`.`INVENTORY_ID` = `INVENTORY`.`INVENTORY_ID`
           LEFT JOIN
              `FILM`
              ON `INVENTORY`.`FILM_ID` = `FILM`.`FILM_ID`
        WHERE
           `RENTAL`.`RETURN_DATE` IS NULL
           AND RENTAL_DATE + INTERVAL FILM.RENTAL_DURATION DAY < ?
        ORDER BY
           `TITLE` ASC LIMIT 5"
        */

        $overdueFilms = $overdueFilms->get();

        Log::info('Overdue films', [$overdueFilms]);
        self::assertCount(5, $overdueFilms);
        /*

        {"Illuminate\\Support\\Collection":[{
        "customer":"OLVERA, DWAYNE","phone":"62127829280","title":"ACADEMY DINOSAUR"},
        {"customer":"HUEY, BRANDON","phone":"99883471275","title":"ACE GOLDFINGER"},
        {"customer":"OWENS, CARMEN","phone":"272234298332","title":"AFFAIR PREJUDICE"},
        {"customer":"HANNON, SETH","phone":"864392582257","title":"AFRICAN EGG"},
        {"customer":"COLE, TRACY","phone":"371490777743","title":"ALI FOREVER"}]}

        */
    }
}

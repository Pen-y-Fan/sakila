<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Country;
use App\Models\Film;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

/**
 * Exploring further the where keywords
 * Based on
 * https://www.youtube.com/watch?v=GhWLAS4FkLI&list=PLkyrdyGDWthC-yd9n8R3CEauJC4sFl-kj&index=4
 */
class ExploringFurtherTheWhereKeywordsTest extends TestCase
{
    public function testWhereInExample(): void
    {
        $selection = ['Afghanistan', 'Bangladesh', 'China'];

        $countries = Country::whereIn('country', $selection)
            ->orderBy('id', 'DESC')
            ->get();

        Log::info('Countries Where In Example', [$countries]);

        self::assertCount(3, $countries);

        foreach ($countries as $country) {
            self::assertContains($country->country, $selection);
        }
    }

    public function testTenFilmsWithReplacementCostBetween1999And2099(): void
    {

        /*
            SELECT film_id, title, special_features, replacement_cost
            FROM film
            WHERE replacement_cost BETWEEN 19.99 AND 20.99
            ORDER BY film_id
            LIMIT 10;
        */

        $films = Film::whereBetween('replacement_cost', [19.99, 20.99])
            ->orderBy('id')
            ->limit(10)
            ->get();

        self::assertCount(10, $films);

        foreach ($films as $film) {
            self::assertGreaterThanOrEqual(19.99, $film->replacement_cost);
            self::assertLessThanOrEqual(20.99, $film->replacement_cost);
        }

        Log::info('Ten films with replacement cost between 19.99 and 20.99', [$films]);
        /*
        [2021-11-04 21:50:51] testing.INFO: Ten films with replacement cost between 19.99 and 20.99
        [
        {"Illuminate\\Support\\Collection":
        [
        {"film_id":1,"title":"ACADEMY DINOSAUR","special_features":"Deleted Scenes,Behind the Scenes","replacement_cost":"20.99"},
        {"film_id":19,"title":"AMADEUS HOLY","special_features":"Commentaries,Deleted Scenes,Behind the Scenes","replacement_cost":"20.99"},
        {"film_id":24,"title":"ANALYZE HOOSIERS","special_features":"Trailers,Behind the Scenes","replacement_cost":"19.99"},
        {"film_id":50,"title":"BAKED CLEOPATRA","special_features":"Commentaries,Behind the Scenes","replacement_cost":"20.99"},
        {"film_id":59,"title":"BEAR GRACELAND","special_features":"Deleted Scenes","replacement_cost":"20.99"},
        {"film_id":65,"title":"BEHAVIOR RUNAWAY","special_features":"Trailers,Deleted Scenes,Behind the Scenes","replacement_cost":"20.99"},
        {"film_id":92,"title":"BOWFINGER GABLES","special_features":"Trailers,Deleted Scenes","replacement_cost":"19.99"},
        {"film_id":102,"title":"BUBBLE GROSSE","special_features":"Trailers,Commentaries,Deleted Scenes,Behind the Scenes","replacement_cost":"20.99"},
        {"film_id":108,"title":"BUTCH PANTHER","special_features":"Trailers,Commentaries,Deleted Scenes","replacement_cost":"19.99"},
        {"film_id":113,"title":"CALIFORNIA BIRDS","special_features":"Trailers,Commentaries,Deleted Scenes","replacement_cost":"19.99"}
        ]}]
        */
    }

    public function testTenFilmsWithReplacementCostNotBetween1899And2099(): void
    {

        /*
            SELECT film_id, title, special_features, replacement_cost
            FROM film
            WHERE replacement_cost NOT BETWEEN 18.99 AND 20.99
            ORDER BY film_id
            LIMIT 10;
        */

        $films = Film::whereNotBetween('replacement_cost', [18.99, 20.99])
            ->orderBy('id')
            ->limit(10)
            ->get();

        self::assertCount(10, $films);

        foreach ($films as $film) {
            if ($film->replacement_cost > 20) {
                self::assertGreaterThanOrEqual(20.99, $film->replacement_cost);
            } else {
                self::assertLessThanOrEqual(18.99, $film->replacement_cost);
            }
        }

        Log::info('Ten films with replacement cost not between 18.99 and 20.99', [$films]);
        /*
            [2021-11-04 21:59:42] testing.INFO: Ten films with replacement cost not between 18.99 and 20.99
        [
        {"Illuminate\\Support\\Collection":
        [{"film_id":2,"title":"ACE GOLDFINGER","special_features":"Trailers,Deleted Scenes","replacement_cost":"12.99"},
        {"film_id":4,"title":"AFFAIR PREJUDICE","special_features":"Commentaries,Behind the Scenes","replacement_cost":"26.99"},
        {"film_id":5,"title":"AFRICAN EGG","special_features":"Deleted Scenes","replacement_cost":"22.99"},
        {"film_id":6,"title":"AGENT TRUMAN","special_features":"Deleted Scenes","replacement_cost":"17.99"},
        {"film_id":7,"title":"AIRPLANE SIERRA","special_features":"Trailers,Deleted Scenes","replacement_cost":"28.99"},
        {"film_id":8,"title":"AIRPORT POLLOCK","special_features":"Trailers","replacement_cost":"15.99"},
        {"film_id":9,"title":"ALABAMA DEVIL","special_features":"Trailers,Deleted Scenes","replacement_cost":"21.99"},
        {"film_id":10,"title":"ALADDIN CALENDAR","special_features":"Trailers,Deleted Scenes","replacement_cost":"24.99"},
        {"film_id":11,"title":"ALAMO VIDEOTAPE","special_features":"Commentaries,Behind the Scenes","replacement_cost":"16.99"},
        {"film_id":12,"title":"ALASKA PHANTOM","special_features":"Commentaries,Deleted Scenes","replacement_cost":"22.99"}
        ]}]

        */
    }

    public function testAfricanEggOrAgentTruman(): void
    {

        /*
            SELECT film_id, title, special_features, replacement_cost
            FROM film
            WHERE (title = 'African Egg') OR (title = 'Agent Truman');
        */

        $films = Film::where('title', 'African Egg')
            ->orWhere('title', 'Agent Truman')
            ->get();

        self::assertCount(2, $films);

        Log::info('African Egg or Agent Truman films', [$films]);
        /*
            [2021-11-04 22:14:43] testing.INFO: African Egg or Agent Truman films
        [{"Illuminate\\Support\\Collection":
        [{"film_id":5,"title":"AFRICAN EGG","special_features":"Deleted Scenes","replacement_cost":"22.99"},
        {"film_id":6,"title":"AGENT TRUMAN","special_features":"Deleted Scenes","replacement_cost":"17.99"}]}]
        */
    }
}

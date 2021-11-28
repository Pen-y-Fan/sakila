<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

/**
 * Using joins and conditionals in Query Builder
 * Based on
 * @link https://www.youtube.com/watch?v=I_HmkVB6abQ&list=PLkyrdyGDWthC-yd9n8R3CEauJC4sFl-kj&index=8
 */
class UsingJoinsAndConditionalsInQueryBuilderTest extends TestCase
{
    /**
     * Display categories and number of film in each category where film' language is English
     */
    public function testCountOfFilmCategories(): void
    {
//        self::markTestSkipped('to be converted to model');
        /*

        -- Display categories and number of film in each category where film' language is English

        -- category -> left join -> film category
        -- film category -> inner join -> film
        -- film -> inner join -> language

        SELECT c.name, COUNT(f.film_id) AS film_count
        FROM category AS c
        JOIN film_category fc on c.category_id = fc.category_id
        JOIN film AS f ON fc.film_id = f.film_id
        JOIN `language` l ON f.language_id = l.language_id
        WHERE l.name = 'English'
        GROUP BY c.name
        ORDER BY film_count DESC;

        */


        /*
        $filmCategoryCount = DB::query()
            ->select(['c.name', DB::raw('COUNT(f.film_id) AS film_count')])
            ->from('category', 'c')
            ->leftJoin('film_category AS fc', 'c.category_id', '=', 'fc.category_id')
            ->join('film AS f', 'fc.film_id', '=', 'f.film_id')
            ->join('language AS l', 'f.language_id', '=', 'l.language_id')
            ->where('l.name', 'English')
            ->groupBy('c.name')
            ->orderBy('film_count', 'DESC');
*/

        $filmCategoryCount = Category::withCount(
            [
                'films' => function (Builder $query) {
                    $query->join(
                        'languages',
                        fn($join) => $join->on('films.language_id', '=', 'languages.id')
                            ->where('languages.name', 'English')
                    );
                },
            ]
        )
            ->orderBy('films_count', 'DESC')
            ->get();

        Log::info('Film category count', [$filmCategoryCount]);

        /*
        [2021-11-28 15:55:40] testing.INFO: Film category count
         [{"Illuminate\\Database\\Eloquent\\Collection":[
        {"id":15,"name":"Sports","created_at":"2006-02-15T04:46:27.000000Z","updated_at":"2006-02-15T04:46:27.000000Z","films_count":74},
        {"id":9,"name":"Foreign","created_at":"2006-02-15T04:46:27.000000Z","updated_at":"2006-02-15T04:46:27.000000Z","films_count":73},
        {"id":8,"name":"Family","created_at":"2006-02-15T04:46:27.000000Z","updated_at":"2006-02-15T04:46:27.000000Z","films_count":69},
        {"id":6,"name":"Documentary","created_at":"2006-02-15T04:46:27.000000Z","updated_at":"2006-02-15T04:46:27.000000Z","films_count":68},
        {"id":2,"name":"Animation","created_at":"2006-02-15T04:46:27.000000Z","updated_at":"2006-02-15T04:46:27.000000Z","films_count":66},
        {"id":1,"name":"Action","created_at":"2006-02-15T04:46:27.000000Z","updated_at":"2006-02-15T04:46:27.000000Z","films_count":64},
        {"id":13,"name":"New","created_at":"2006-02-15T04:46:27.000000Z","updated_at":"2006-02-15T04:46:27.000000Z","films_count":63},
        {"id":7,"name":"Drama","created_at":"2006-02-15T04:46:27.000000Z","updated_at":"2006-02-15T04:46:27.000000Z","films_count":62},
        {"id":10,"name":"Games","created_at":"2006-02-15T04:46:27.000000Z","updated_at":"2006-02-15T04:46:27.000000Z","films_count":61},
        {"id":14,"name":"Sci-Fi","created_at":"2006-02-15T04:46:27.000000Z","updated_at":"2006-02-15T04:46:27.000000Z","films_count":61},
        {"id":3,"name":"Children","created_at":"2006-02-15T04:46:27.000000Z","updated_at":"2006-02-15T04:46:27.000000Z","films_count":60},
        {"id":5,"name":"Comedy","created_at":"2006-02-15T04:46:27.000000Z","updated_at":"2006-02-15T04:46:27.000000Z","films_count":58},
        {"id":4,"name":"Classics","created_at":"2006-02-15T04:46:27.000000Z","updated_at":"2006-02-15T04:46:27.000000Z","films_count":57},
        {"id":16,"name":"Travel","created_at":"2006-02-15T04:46:27.000000Z","updated_at":"2006-02-15T04:46:27.000000Z","films_count":57},
        {"id":11,"name":"Horror","created_at":"2006-02-15T04:46:27.000000Z","updated_at":"2006-02-15T04:46:27.000000Z","films_count":56},
        {"id":12,"name":"Music","created_at":"2006-02-15T04:46:27.000000Z","updated_at":"2006-02-15T04:46:27.000000Z","films_count":51}]}]

        */

        self::assertCount(16, $filmCategoryCount);
        self::assertSame(74, $filmCategoryCount->first()->films_count);
        self::assertSame('Sports', $filmCategoryCount->first()->name);
        self::assertSame(51, $filmCategoryCount->last()->films_count);
        self::assertSame('Music', $filmCategoryCount->last()->name);
    }
}

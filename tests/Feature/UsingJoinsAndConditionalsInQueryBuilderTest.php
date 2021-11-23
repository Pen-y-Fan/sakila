<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
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
        self::markTestSkipped('to be converted to model');
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

        $filmCategoryCount = DB::query()
            ->select(['c.name', DB::raw('COUNT(f.film_id) AS film_count')])
            ->from('category', 'c')
            ->leftJoin('film_category AS fc', 'c.category_id', '=', 'fc.category_id')
            ->join('film AS f', 'fc.film_id', '=', 'f.film_id')
            ->join('language AS l', 'f.language_id', '=', 'l.language_id')
            ->where('l.name', 'English')
            ->groupBy('c.name')
            ->orderBy('film_count', 'DESC');

        Log::info('Film category count SQL', [$filmCategoryCount->toSQL()]);

        $filmCategoryCount = $filmCategoryCount->get();

        /*
        select `c`.`name`, COUNT(f.film_id) AS film_count
        from `category` as `c`
        left join `film_category` as `fc` on `c`.`category_id` = `fc`.`category_id`
        inner join `film` as `f` on `fc`.`film_id` = `f`.`film_id`
        inner join `language` as `l` on `f`.`language_id` = `l`.`language_id`
        where `l`.`name` = ?
        group by `c`.`name`
        order by `film_count` desc
        */

        Log::info('Film category count', [$filmCategoryCount]);
        self::assertCount(16, $filmCategoryCount);
        /*

        [2021-11-07 12:05:21] testing.INFO: Film category count [
        {"Illuminate\\Support\\Collection":[
        {"name":"Sports","film_count":74},
        {"name":"Foreign","film_count":73},
        {"name":"Family","film_count":69},
        {"name":"Documentary","film_count":68},
        {"name":"Animation","film_count":66},
        {"name":"Action","film_count":64},
        {"name":"New","film_count":63},
        {"name":"Drama","film_count":62},
        {"name":"Sci-Fi","film_count":61},
        {"name":"Games","film_count":61},
        {"name":"Children","film_count":60},
        {"name":"Comedy","film_count":58},
        {"name":"Classics","film_count":57},
        {"name":"Travel","film_count":57},
        {"name":"Horror","film_count":56},
        {"name":"Music","film_count":51}]}]
        */
    }

    public function testCountOfFilmCategoriesUsingClosure(): void
    {
        self::markTestSkipped('to be converted to model');
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

        $filmCategoryCount = DB::query()
            ->select(['c.name', DB::raw('COUNT(f.film_id) AS film_count')])
            ->from('category', 'c')
            ->leftJoin('film_category AS fc', 'c.category_id', '=', 'fc.category_id')
            ->join('film AS f', 'fc.film_id', '=', 'f.film_id')
            ->join(
                'language AS l',
                fn ($join) => $join->on('f.language_id', '=', 'l.language_id')->where('l.name', 'English')
            )
            ->groupBy('c.name')
            ->orderBy('film_count', 'DESC');

        Log::info('Film category count SQL using closure', [$filmCategoryCount->toSQL()]);

        $filmCategoryCount = $filmCategoryCount->get();

        /*
        [2021-11-07 12:29:44] testing.INFO: Film category count SQL using closure
        ["
        select `c`.`name`, COUNT(f.film_id) AS film_count
        from `category` as `c`
        left join `film_category` as `fc`
            on `c`.`category_id` = `fc`.`category_id`
        inner join `film` as `f`
            on `fc`.`film_id` = `f`.`film_id`
        inner join `language` as `l`
            on `f`.`language_id` = `l`.`language_id` and `l`.`name` = ?
        group by `c`.`name`
        order by `film_count` desc
        "]


        */
        self::assertCount(16, $filmCategoryCount);
        Log::info('Film category count using closure', [$filmCategoryCount]);

        /*

        [2021-11-07 12:29:44] testing.INFO: Film category count using closure
        [{"Illuminate\\Support\\Collection":[
        {"name":"Sports","film_count":74},
        {"name":"Foreign","film_count":73},
        {"name":"Family","film_count":69},
        {"name":"Documentary","film_count":68},
        {"name":"Animation","film_count":66},
        {"name":"Action","film_count":64},
        {"name":"New","film_count":63},
        {"name":"Drama","film_count":62},
        {"name":"Sci-Fi","film_count":61},
        {"name":"Games","film_count":61},
        {"name":"Children","film_count":60},
        {"name":"Comedy","film_count":58},
        {"name":"Classics","film_count":57},
        {"name":"Travel","film_count":57},
        {"name":"Horror","film_count":56},
        {"name":"Music","film_count":51}]
        }]

        */

        $sum = $filmCategoryCount->sum('film_count');
        Log::info('total films', [$sum]);

        /*
                [2021-11-07 13:01:52] testing.INFO: total films [1000]
        */

        self::assertSame(1000, $sum);
    }
}

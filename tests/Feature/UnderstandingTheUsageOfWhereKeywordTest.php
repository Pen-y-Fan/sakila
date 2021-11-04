<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

/**
 * Understanding the usage of WHERE keyword
 * Based on
 * https://www.youtube.com/watch?v=wP73mHNwnLE&list=PLkyrdyGDWthC-yd9n8R3CEauJC4sFl-kj&index=3
 */
class UnderstandingTheUsageOfWhereKeywordTest extends TestCase
{
    /**
     * When the operator is omitted the default is an equals.
     */
    public function testOperatorDefaultsToEquals(): void
    {

        // SELECT * FROM actor WHERE last_name = 'BERRY';

        $actorsCalledBerry = DB::table('actor')
            ->where('last_name', 'BERRY') // Note: no '=' operator
            ->get();

        self::assertCount(3, $actorsCalledBerry);

        foreach ($actorsCalledBerry as $actor) {
            self::assertSame('BERRY', $actor->last_name);
        }
        Log::info('Actors called Berry', [$actorsCalledBerry]);

        /*
        [2021-11-03 22:56:45] testing.INFO: Actors called Berry
        [
        {"Illuminate\\Support\\Collection":
        [
        {"actor_id":12,"first_name":"KARL","last_name":"BERRY","last_update":"2006-02-15 04:34:33"},
        {"actor_id":60,"first_name":"HENRY","last_name":"BERRY","last_update":"2006-02-15 04:34:33"},
        {"actor_id":91,"first_name":"CHRISTOPHER","last_name":"BERRY","last_update":"2006-02-15 04:34:33"}
        ]}]
        */
    }

    public function testWhereClausesCanBeChained(): void
    {

        // SELECT * FROM actor WHERE last_name = 'BERRY' AND first_name = 'KARL';

        $actorsCalledKarlBerry = DB::table('actor')
            ->where('last_name', 'BERRY')
            ->where('first_name', 'KARL')
            ->get();

        self::assertCount(1, $actorsCalledKarlBerry);

        foreach ($actorsCalledKarlBerry as $actor) {
            self::assertSame('BERRY', $actor->last_name);
            self::assertSame('KARL', $actor->first_name);
        }
        Log::info('Actors called Karl Berry', [$actorsCalledKarlBerry]);

        /*
        [2021-11-03 23:02:20] testing.INFO: Actors called Karl Berry
        [
        {"Illuminate\\Support\\Collection":
        [
        {"actor_id":12,"first_name":"KARL","last_name":"BERRY","last_update":"2006-02-15 04:34:33"}
        ]}]
        */
    }

    public function testWhereClauseCanBeAnArray(): void
    {

        // SELECT * FROM actor WHERE last_name = 'BERRY' AND first_name = 'KARL';

        $actorsCalledKarlBerry = DB::table('actor')
            ->where(
                [
                    ['last_name', 'BERRY'],
                    ['first_name', 'KARL']
                ]
            )
            ->get();

        self::assertCount(1, $actorsCalledKarlBerry);

        foreach ($actorsCalledKarlBerry as $actor) {
            self::assertSame('BERRY', $actor->last_name);
            self::assertSame('KARL', $actor->first_name);
        }
        Log::info('Actors called Karl Berry', [$actorsCalledKarlBerry]);

        /*
        [2021-11-03 23:07:01] testing.INFO: Actors called Karl Berry
        [
        {"Illuminate\\Support\\Collection":
        [
        {"actor_id":12,"first_name":"KARL","last_name":"BERRY","last_update":"2006-02-15 04:34:33"}
        ]}]
        */
    }

    /**
     * Where can also take a closure (more examples in future)
     */
    public function testWhereClauseCanBeAClosure(): void
    {

        // SELECT * FROM actor WHERE last_name = 'BERRY' AND first_name = 'KARL';

        $actorsCalledKarlBerry = DB::table('actor')
            ->where(function ($query) {
                $query->where(
                    [
                        ['first_name', 'KARL'],
                        ['last_name', 'BERRY']
                    ]
                );
            })
            ->get();

        self::assertCount(1, $actorsCalledKarlBerry);

        foreach ($actorsCalledKarlBerry as $actor) {
            self::assertSame('BERRY', $actor->last_name);
            self::assertSame('KARL', $actor->first_name);
        }
        Log::info('Actors called Karl Berry', [$actorsCalledKarlBerry]);

        /*
        [2021-11-03 23:07:01] testing.INFO: Actors called Karl Berry
        [
        {"Illuminate\\Support\\Collection":
        [
        {"actor_id":12,"first_name":"KARL","last_name":"BERRY","last_update":"2006-02-15 04:34:33"}
        ]}]
        */
    }

    /**
     * Top 10 list of the most popular last names, with a count of how many actors have used it.
     * More complex example with group by and order by (limit also added for easier testing)
     */
    public function testWhereWithOrderByAndGroupBy(): void
    {
        /*
                SELECT
                    last_name,
                    COUNT(*) AS actor_count
                FROM actor
                GROUP BY last_name
                ORDER BY actor_count DESC
                LIMIT 10;
        */
        $TopTenListOfActorsLastNames = DB::table('actor')
            ->select(
                [
                    'last_name',
                    DB::raw('COUNT(*) AS actor_count')
                ]
            )
            ->groupBy('last_name')
            ->orderBy('actor_count', 'DESC')
            ->limit(10)
            ->get();

        self::assertCount(10, $TopTenListOfActorsLastNames);

        // Most popular is {"last_name":"KILMER","actor_count":5},
        self::assertSame('KILMER', $TopTenListOfActorsLastNames[0]->last_name);
        self::assertSame(5, $TopTenListOfActorsLastNames[0]->actor_count);


        Log::info(
            'Top 10 list of the most popular last names, with a count of how many actors have used it',
            [$TopTenListOfActorsLastNames]
        );

        /*
        [2021-11-03 23:48:09] testing.INFO: Top 10 list of the most popular last names, with a count of how many actors have used it
        [
        {"Illuminate\\Support\\Collection":
        [
        {"last_name":"KILMER","actor_count":5},
        {"last_name":"NOLTE","actor_count":4},
        {"last_name":"TEMPLE","actor_count":4},
        {"last_name":"ALLEN","actor_count":3},
        {"last_name":"GUINESS","actor_count":3},
        {"last_name":"KEITEL","actor_count":3},
        {"last_name":"WILLIAMS","actor_count":3},
        {"last_name":"AKROYD","actor_count":3},
        {"last_name":"BERRY","actor_count":3},
        {"last_name":"GARLAND","actor_count":3}
        ]}]
        */
    }
}

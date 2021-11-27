<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Actor;
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

        $actorsCalledBerry = Actor::where('last_name', 'BERRY') // Note: no '=' operator
            ->get();

        self::assertCount(3, $actorsCalledBerry);

        foreach ($actorsCalledBerry as $actor) {
            self::assertSame('BERRY', $actor->last_name);
        }
        Log::info('Actors called Berry', [$actorsCalledBerry]);

        /*

            [2021-11-27 20:19:42] testing.INFO: Actors called Berry
        [{"Illuminate\\Database\\Eloquent\\Collection":
        [{"id":12,"first_name":"KARL","last_name":"BERRY","created_at":"2006-02-15T04:34:33.000000Z","updated_at":"2006-02-15T04:34:33.000000Z"},
        {"id":60,"first_name":"HENRY","last_name":"BERRY","created_at":"2006-02-15T04:34:33.000000Z","updated_at":"2006-02-15T04:34:33.000000Z"},
        {"id":91,"first_name":"CHRISTOPHER","last_name":"BERRY","created_at":"2006-02-15T04:34:33.000000Z","updated_at":"2006-02-15T04:34:33.000000Z"}]}]

        */
    }

    public function testWhereClausesCanBeChained(): void
    {

        // SELECT * FROM actor WHERE last_name = 'BERRY' AND first_name = 'KARL';

        $actorsCalledKarlBerry = Actor::select(['id', 'first_name', 'last_name'])
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
            [2021-11-27 21:05:49] testing.INFO: Actors called Karl Berry
        [{"Illuminate\\Database\\Eloquent\\Collection":[{"id":12,"first_name":"KARL","last_name":"BERRY"}]}]

        */
    }

    public function testWhereClauseCanBeAnArray(): void
    {
        // SELECT * FROM actor WHERE last_name = 'BERRY' AND first_name = 'KARL';

        $actorsCalledKarlBerry = Actor::select(['id', 'first_name', 'last_name'])
            ->where(
                [
                    ['last_name', 'BERRY'],
                    ['first_name', 'KARL'],
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

        [2021-11-27 21:07:36] testing.INFO: Actors called Karl Berry
        [{"Illuminate\\Database\\Eloquent\\Collection":[{"id":12,"first_name":"KARL","last_name":"BERRY"}
        ]}]

        */
    }

    /**
     * Where can also take a closure (more examples in future)
     */
    public function testWhereClauseCanBeAClosure(): void
    {
        // SELECT * FROM actor WHERE last_name = 'BERRY' AND first_name = 'KARL';

        $actorsCalledKarlBerry = Actor::select(['id', 'first_name', 'last_name'])
            ->where(function ($query) {
                $query->where(
                    [
                        ['first_name', 'KARL'],
                        ['last_name', 'BERRY'],
                    ]
                );
            })
            ->get();

        self::assertCount(1, $actorsCalledKarlBerry);

        foreach ($actorsCalledKarlBerry as $actor) {
            self::assertSame('BERRY', $actor->last_name);
            self::assertSame('KARL', $actor->first_name);
        }
        Log::info('Actors called Karl Berry closure', [$actorsCalledKarlBerry]);

        /*
            [2021-11-27 21:09:45] testing.INFO: Actors called Karl Berry closure
        [{"Illuminate\\Database\\Eloquent\\Collection":
        [{"id":12,"first_name":"KARL","last_name":"BERRY"}
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
        $TopTenListOfActorsLastNames = Actor::select(
                [
                    'last_name',
                    DB::raw('COUNT(*) AS actor_count'),
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
            [2021-11-27 21:11:55] testing.INFO: Top 10 list of the most popular last names, with a count of how many actors have used it
        [{"Illuminate\\Database\\Eloquent\\Collection":[
        {"last_name":"KILMER","actor_count":5},
        {"last_name":"TEMPLE","actor_count":4},
        {"last_name":"NOLTE","actor_count":4},
        {"last_name":"PECK","actor_count":3},
        {"last_name":"HOFFMAN","actor_count":3},
        {"last_name":"HOPKINS","actor_count":3},
        {"last_name":"ZELLWEGER","actor_count":3},
        {"last_name":"JOHANSSON","actor_count":3},
        {"last_name":"DAVIS","actor_count":3},
        {"last_name":"AKROYD","actor_count":3}]}]

        */
    }
}

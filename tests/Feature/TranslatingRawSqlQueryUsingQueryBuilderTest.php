<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class TranslatingRawSqlQueryUsingQueryBuilderTest extends TestCase
{
    /**
     * Based on
     * @link https://www.youtube.com/watch?v=OIZmTyMq6cU&list=PLkyrdyGDWthC-yd9n8R3CEauJC4sFl-kj&index=2
     *
     * @return void
     */
    public function testRawSqlToQueryBuilder(): void
    {
        // SELECT * FROM actor WHERE last_name = 'BERRY';

        $actorsCalledBerry = DB::table('actor')
            ->where('last_name', '=', 'Berry')
            ->get();

        self::assertCount(3, $actorsCalledBerry);

        foreach ($actorsCalledBerry as $actor) {
            self::assertSame('BERRY', $actor->last_name);
        }
        Log::info('Actors called Berry', [$actorsCalledBerry]);

        /*
        [2021-11-03 22:41:51] testing.INFO: Actors called Berry
        [
        {"Illuminate\\Support\\Collection":
        [
        {"actor_id":12,"first_name":"KARL","last_name":"BERRY","last_update":"2006-02-15 04:34:33"},
        {"actor_id":60,"first_name":"HENRY","last_name":"BERRY","last_update":"2006-02-15 04:34:33"},
        {"actor_id":91,"first_name":"CHRISTOPHER","last_name":"BERRY","last_update":"2006-02-15 04:34:33"}
        ]}]
        */

    }
}

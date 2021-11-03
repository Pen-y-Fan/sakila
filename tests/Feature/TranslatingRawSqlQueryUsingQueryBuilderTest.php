<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
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

        /*
        | 12 | KARL | BERRY | 2006-02-15 04:34:33 |
        | 60 | HENRY | BERRY | 2006-02-15 04:34:33 |
        | 91 | CHRISTOPHER | BERRY | 2006-02-15 04:34:33 |
        */

    }
}

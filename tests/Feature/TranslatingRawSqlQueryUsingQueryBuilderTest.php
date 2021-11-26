<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Actor;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class TranslatingRawSqlQueryUsingQueryBuilderTest extends TestCase
{
    /**
     * Based on
     * @link https://www.youtube.com/watch?v=OIZmTyMq6cU&list=PLkyrdyGDWthC-yd9n8R3CEauJC4sFl-kj&index=2
     */
    public function testRawSqlToQueryBuilder(): void
    {
        // SELECT * FROM actor WHERE last_name = 'BERRY';

        $actorsCalledBerry = Actor::where('last_name', '=', 'Berry')
            ->get();

        self::assertCount(3, $actorsCalledBerry);

        foreach ($actorsCalledBerry as $actor) {
            self::assertSame('BERRY', $actor->last_name);
        }
        Log::info('Actors called Berry', [$actorsCalledBerry]);

        /*
        [2021-11-26 20:18:04] testing.INFO: Actors called Berry
        [{"Illuminate\\Database\\Eloquent\\Collection":
        [
        {"id":12,"first_name":"KARL","last_name":"BERRY","created_at":"2006-02-15T04:34:33.000000Z","updated_at":"2006-02-15T04:34:33.000000Z"},
        {"id":60,"first_name":"HENRY","last_name":"BERRY","created_at":"2006-02-15T04:34:33.000000Z","updated_at":"2006-02-15T04:34:33.000000Z"},
        {"id":91,"first_name":"CHRISTOPHER","last_name":"BERRY","created_at":"2006-02-15T04:34:33.000000Z","updated_at":"2006-02-15T04:34:33.000000Z"}
        ]}]
        */
    }
}

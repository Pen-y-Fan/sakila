<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

/**
 * Based on
 * @link https://www.youtube.com/watch?v=5R0tVpkuQ1M&list=PLkyrdyGDWthC-yd9n8R3CEauJC4sFl-kj&index=6
 */
class ExploringSubQueriesUsingBuilderQueryTest extends TestCase
{
    /**
     * Display the titles of movies with the letters K and Q
     */
    public function testDisplayTheTitlesOfMoviesWithTheLettersKAndQ(): void
    {
        self::markTestIncomplete('to be converted to Model');
        /*

        SELECT film_id, title
        FROM film
        WHERE title LIKE 'K%' OR title LIKE 'Q%'
        ORDER BY title

        */

        /** @noinspection SpellCheckingInspection */
        $moviesBeginningWithKAndQ = DB::table('film')
            ->select(['film_id', 'title'])
            ->where('title', 'LIKE', 'K%')
            ->orWhere('title', 'LIKE', 'Q%')
            ->orderBy('title')
            ->get();

        self::assertCount(15, $moviesBeginningWithKAndQ);

        Log::info('Movies beginning with K and Q', [$moviesBeginningWithKAndQ]);

        /*

        [2021-11-06 11:18:30] testing.INFO: Movies beginning with K and Q
        [{"Illuminate\\Support\\Collection":[
        {"film_id":493,"title":"KANE EXORCIST"},
        {"film_id":494,"title":"KARATE MOON"},
        {"film_id":495,"title":"KENTUCKIAN GIANT"},
        {"film_id":496,"title":"KICK SAVANNAH"},
        {"film_id":497,"title":"KILL BROTHERHOOD"},
        {"film_id":498,"title":"KILLER INNOCENT"},
        {"film_id":499,"title":"KING EVOLUTION"},
        {"film_id":500,"title":"KISS GLORY"},
        {"film_id":501,"title":"KISSING DOLLS"},
        {"film_id":502,"title":"KNOCK WARLOCK"},
        {"film_id":503,"title":"KRAMER CHOCOLATE"},
        {"film_id":504,"title":"KWAI HOMEWARD"},
        {"film_id":706,"title":"QUEEN LUKE"},
        {"film_id":707,"title":"QUEST MUSSOLINI"},
        {"film_id":708,"title":"QUILLS BULL"}]}]

        */
    }

    /**
     * Display the titles of movies with the letters K and Q whose language is English
     * You are only allowed to use sub queries
     */
    public function testDisplayTheTitlesOfMoviesWithTheLettersKAndQWhoseLanguageIsEnglish(): void
    {
        self::markTestIncomplete('to be converted to Model');
        /*

            SELECT film_id, title
            FROM film
            WHERE title LIKE 'K%' OR title LIKE 'Q%'
            AND language_id IN (
                SELECT language_id
                FROM language
                WHERE name = 'english'
                    )
            ORDER BY title

        */

        /** @noinspection SpellCheckingInspection */
        $englishKandQMovies = DB::table('film')
            ->select(['film_id', 'title'])
            ->where('title', 'LIKE', 'K%')
            ->orWhere('title', 'LIKE', 'Q%')
            ->whereIn(
                'language_id',
                fn ($query) => $query->select('language_id')->from('language')->where('name', 'english')
            )
            ->orderBy('title')
            ->get();

        self::assertCount(15, $englishKandQMovies);

        Log::info('English K and Q movies', [$englishKandQMovies]);

        /*

        [2021-11-06 11:18:30] testing.INFO: English K and Q movies
        [{"Illuminate\\Support\\Collection":[
        {"film_id":493,"title":"KANE EXORCIST"},
        {"film_id":494,"title":"KARATE MOON"},
        {"film_id":495,"title":"KENTUCKIAN GIANT"},
        {"film_id":496,"title":"KICK SAVANNAH"},
        {"film_id":497,"title":"KILL BROTHERHOOD"},
        {"film_id":498,"title":"KILLER INNOCENT"},
        {"film_id":499,"title":"KING EVOLUTION"},
        {"film_id":500,"title":"KISS GLORY"},
        {"film_id":501,"title":"KISSING DOLLS"},
        {"film_id":502,"title":"KNOCK WARLOCK"},
        {"film_id":503,"title":"KRAMER CHOCOLATE"},
        {"film_id":504,"title":"KWAI HOMEWARD"},
        {"film_id":706,"title":"QUEEN LUKE"},
        {"film_id":707,"title":"QUEST MUSSOLINI"},
        {"film_id":708,"title":"QUILLS BULL"}
        ]}]

        */
    }
}

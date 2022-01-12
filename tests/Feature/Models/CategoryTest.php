<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Category;
use App\Models\Film;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    public function testTheFirstCategoryIsAction(): void
    {
        $firstCategory = Category::first();

        $this->assertSame('Action', $firstCategory->name);
    }

    public function testTheLastCategoryIsTravel(): void
    {
        $lastCategory = Category::find(16);

        $this->assertSame('Travel', $lastCategory->name);
    }

    public function testActionHasSixtyFourFilms(): void
    {
        $firstCategory = Category::withCount('films')->first();

        $this->assertSame(64, $firstCategory->films_count);
    }

    public function testActionHasAmadeusHolyAndWorstBanger(): void
    {
        $firstCategory = Category::with('films:title')->first();

        $this->assertSame('AMADEUS HOLY', $firstCategory->films->first()->title);
        $this->assertSame('WORST BANGER', $firstCategory->films->last()->title);
    }
    public function testLongClassicFilmsWithTheActors(): void
    {
        $classicFilms = Film::with(['categories', 'actors'])
            ->whereHas('categories', fn($query) => $query->where('name', 'Classics'))
            ->where('length', '>', 170)
            ->orderByDesc('length')
        ->get();

        $this->assertSame('CONSPIRACY SPIRIT', $classicFilms->first()->title);
        $this->assertSame('DAN', $classicFilms->last()->actors->first()->first_name);
        $this->assertSame('JERK PAYCHECK', $classicFilms->last()->title);
        $this->assertCount(5, $classicFilms);
/*
[2022-01-12 21:12:28] testing.INFO: long classic films with the actors [{"Illuminate\\Database\\Eloquent\\Collection":
[{"id":180,"title":"CONSPIRACY SPIRIT","description":"A Awe-Inspiring Story of a Student And a Frisbee who must Conquer a Crocodile in An Abandoned Mine Shaft","release_year":2006,"language_id":1,"original_language_id":null,"rental_duration":4,"rental_rate":"2.99","length":184,"replacement_cost":"27.99","rating":"PG-13","special_features":"Trailers,Commentaries","created_at":"2006-02-15T05:03:42.000000Z","updated_at":"2006-02-15T05:03:42.000000Z",
"categories":[{"id":4,"name":"Classics","created_at":"2006-02-15T04:46:27.000000Z","updated_at":"2006-02-15T04:46:27.000000Z","pivot":{"film_id":180,"category_id":4}}],
"actors":[]},
{"id":249,"title":"DRACULA CRYSTAL","description":"A Thrilling Reflection of a Feminist And a Cat who must Find a Frisbee in An Abandoned Fun House","release_year":2006,"language_id":1,"original_language_id":null,"rental_duration":7,"rental_rate":"0.99","length":176,"replacement_cost":"26.99","rating":"G","special_features":"Commentaries","created_at":"2006-02-15T05:03:42.000000Z","updated_at":"2006-02-15T05:03:42.000000Z",
"categories":[{"id":4,"name":"Classics","created_at":"2006-02-15T04:46:27.000000Z","updated_at":"2006-02-15T04:46:27.000000Z","pivot":{"film_id":249,"category_id":4}}],
"actors":[{"id":2,"first_name":"NICK","last_name":"WAHLBERG","created_at":"2006-02-15T04:34:33.000000Z","updated_at":"2006-02-15T04:34:33.000000Z","pivot":{"film_id":249,"actor_id":2}}]},
{"id":523,"title":"LIGHTS DEER","description":"A Unbelieveable Epistle of a Dog And a Woman who must Confront a Moose in The Gulf of Mexico","release_year":2006,"language_id":1,"original_language_id":null,"rental_duration":7,"rental_rate":"0.99","length":174,"replacement_cost":"21.99","rating":"R","special_features":"Commentaries","created_at":"2006-02-15T05:03:42.000000Z","updated_at":"2006-02-15T05:03:42.000000Z",
"categories":[{"id":4,"name":"Classics","created_at":"2006-02-15T04:46:27.000000Z","updated_at":"2006-02-15T04:46:27.000000Z","pivot":{"film_id":523,"category_id":4}}],
"actors":[{"id":8,"first_name":"MATTHEW","last_name":"JOHANSSON","created_at":"2006-02-15T04:34:33.000000Z","updated_at":"2006-02-15T04:34:33.000000Z","pivot":{"film_id":523,"actor_id":8}},
    {"id":32,"first_name":"TIM","last_name":"HACKMAN","created_at":"2006-02-15T04:34:33.000000Z","updated_at":"2006-02-15T04:34:33.000000Z","pivot":{"film_id":523,"actor_id":32}}]},
{"id":190,"title":"CREEPERS KANE","description":"A Awe-Inspiring Reflection of a Squirrel And a Boat who must Outrace a Car in A Jet Boat","release_year":2006,"language_id":1,"original_language_id":null,"rental_duration":5,"rental_rate":"4.99","length":172,"replacement_cost":"23.99","rating":"NC-17","special_features":"Trailers,Behind the Scenes","created_at":"2006-02-15T05:03:42.000000Z","updated_at":"2006-02-15T05:03:42.000000Z",
"categories":[{"id":4,"name":"Classics","created_at":"2006-02-15T04:46:27.000000Z","updated_at":"2006-02-15T04:46:27.000000Z","pivot":{"film_id":190,"category_id":4}}],
"actors":[]},
{"id":484,"title":"JERK PAYCHECK","description":"A Touching Character Study of a Pastry Chef And a Database Administrator who must Reach a A Shark in Ancient Japan","release_year":2006,"language_id":1,"original_language_id":null,"rental_duration":3,"rental_rate":"2.99","length":172,"replacement_cost":"13.99","rating":"NC-17","special_features":"Trailers,Commentaries,Deleted Scenes,Behind the Scenes","created_at":"2006-02-15T05:03:42.000000Z","updated_at":"2006-02-15T05:03:42.000000Z",
"categories":[{"id":4,"name":"Classics","created_at":"2006-02-15T04:46:27.000000Z","updated_at":"2006-02-15T04:46:27.000000Z","pivot":{"film_id":484,"category_id":4}}],
"actors":[{"id":18,"first_name":"DAN","last_name":"TORN","created_at":"2006-02-15T04:34:33.000000Z","updated_at":"2006-02-15T04:34:33.000000Z","pivot":{"film_id":484,"actor_id":18}},
    {"id":36,"first_name":"BURT","last_name":"DUKAKIS","created_at":"2006-02-15T04:34:33.000000Z","updated_at":"2006-02-15T04:34:33.000000Z","pivot":{"film_id":484,"actor_id":36}}]}]}]

        */
    }
}

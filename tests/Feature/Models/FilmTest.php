<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Film;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class FilmTest extends TestCase
{
    public function testTheFirstFilmIsAcademyDinosaur(): void
    {
        /*
                id,title,description,release_year,language_id,original_language_id,rental_duration,rental_rate,length,replacement_cost,rating,special_features,created_at,updated_at
        1,ACADEMY DINOSAUR,A Epic Drama of a Feminist And a Mad Scientist who must Battle a Teacher in The Canadian Rockies,2006,1,,6,0.99,86,20.99,PG,"Deleted Scenes,Behind the Scenes",2006-02-15 05:03:42,2006-02-15 05:03:42
        */

        $firstFilm = Film::first();

        $this->assertSame('ACADEMY DINOSAUR', $firstFilm->title);
        $this->assertSame('A Epic Drama of a Feminist And a Mad Scientist who must Battle a Teacher in The Canadian Rockies', $firstFilm->description);
        $this->assertSame(2006, $firstFilm->release_year);
        $this->assertSame(1, $firstFilm->language_id);
        $this->assertSame(6, $firstFilm->rental_duration);
        $this->assertSame('0.99', $firstFilm->rental_rate);
        $this->assertSame(86, $firstFilm->length);
    }

    public function testTheLastFilmIsZorroArk(): void
    {
        /*
            [
        {
            "id": 1000,
          "title": "ZORRO ARK",
          "description": "A Intrepid Panorama of a Mad Scientist And a Boy who must Redeem a Boy in A Monastery",
          "release_year": 2006,
          "language_id": 1,
          "original_language_id": null,
          "rental_duration": 3,
          "rental_rate": 4.99,
          "length": 50,
          "replacement_cost": 18.99,
          "rating": "NC-17",
          "special_features": "Trailers,Commentaries,Behind the Scenes",
          "created_at": "2006-02-15 05:03:42",
          "updated_at": "2006-02-15 05:03:42"
        }
      ]
        */

        $lastFilm = Film::find(1000);

        $this->assertSame('ZORRO ARK', $lastFilm->title);
        $this->assertSame('A Intrepid Panorama of a Mad Scientist And a Boy who must Redeem a Boy in A Monastery', $lastFilm->description);
        $this->assertSame(2006, $lastFilm->release_year);
        $this->assertSame(1, $lastFilm->language_id);
        $this->assertSame(3, $lastFilm->rental_duration);
        $this->assertSame('4.99', $lastFilm->rental_rate);
        $this->assertSame(50, $lastFilm->length);
    }

    public function testTheAcademyDinosaursLanguageIsEnglish(): void
    {
        $academyDinosaur = Film::with('language')->first();

        $this->assertSame(1, $academyDinosaur->language_id);
        $this->assertSame('English', $academyDinosaur->language->name);
    }

    public function testTheAcademyDinosaursCategoriesAreDocumentary(): void
    {
        $academyDinosaur = Film::with('categories')->first();

        $this->assertSame(1, $academyDinosaur->id);
        $this->assertSame(1, $academyDinosaur->categories->count());
        $this->assertSame('Documentary', $academyDinosaur->categories->first()->name);
    }

    public function testTheAcademyDinosaursActors(): void
    {
        $academyDinosaur = Film::with('actors')->first();

        Log::info('testTheAcademyDinosaursActors', [$academyDinosaur]);
        /*
            [2021-11-28 19:52:38] testing.INFO: testTheAcademyDinosaursActors [
        {"App\\Models\\Film":
        {"id":1,"title":"ACADEMY DINOSAUR","description":"A Epic Drama of a Feminist And a Mad Scientist who must Battle a Teacher in The Canadian Rockies","release_year":2006,"language_id":1,"original_language_id":null,"rental_duration":6,"rental_rate":"0.99","length":86,"replacement_cost":"20.99","rating":"PG","special_features":"Deleted Scenes,Behind the Scenes","created_at":"2006-02-15T05:03:42.000000Z","updated_at":"2006-02-15T05:03:42.000000Z",
        "actors":[
            {"id":1,"first_name":"PENELOPE","last_name":"GUINESS","created_at":"2006-02-15T04:34:33.000000Z","updated_at":"2006-02-15T04:34:33.000000Z","pivot":{"film_id":1,"actor_id":1}},
            {"id":10,"first_name":"CHRISTIAN","last_name":"GABLE","created_at":"2006-02-15T04:34:33.000000Z","updated_at":"2006-02-15T04:34:33.000000Z","pivot":{"film_id":1,"actor_id":10}},
            {"id":20,"first_name":"LUCILLE","last_name":"TRACY","created_at":"2006-02-15T04:34:33.000000Z","updated_at":"2006-02-15T04:34:33.000000Z","pivot":{"film_id":1,"actor_id":20}},
            {"id":30,"first_name":"SANDRA","last_name":"PECK","created_at":"2006-02-15T04:34:33.000000Z","updated_at":"2006-02-15T04:34:33.000000Z","pivot":{"film_id":1,"actor_id":30}}
        ]}}]
        */

        $this->assertSame(4, $academyDinosaur->actors->count());
        $this->assertSame('PENELOPE', $academyDinosaur->actors->first()->first_name);
        $this->assertSame('SANDRA', $academyDinosaur->actors->last()->first_name);
    }

    public function testAcademyDinosaursHasAnInventoryOfEight(): void
    {
        $inventoryOfAcademyDinosaurs = Film::with(['inventories'])->first();

        $this->assertSame(8, $inventoryOfAcademyDinosaurs->inventories->count());
    }
}

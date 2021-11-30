<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Actor;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ActorTest extends TestCase
{
    public function testThereAreTwoHundredActors(): void
    {
        $actorCount = Actor::count();

        $this->assertSame(200, $actorCount);
    }

    public function testFirstActorIsPenelopeGuiness(): void
    {
        $actorOne = Actor::first();

        Log::info('FirstActor', [$actorOne]);

        /*
        [2021-11-28 20:04:08] testing.INFO: FirstActor
        [{"App\\Models\\Actor":
        {"id":1,"first_name":"PENELOPE","last_name":"GUINESS","created_at":"2006-02-15T04:34:33.000000Z","updated_at":"2006-02-15T04:34:33.000000Z"}
        }]
        */

        $this->assertSame('PENELOPE', $actorOne->first_name);
        $this->assertSame('GUINESS', $actorOne->last_name);
    }

    public function testLastActorIsThoraTemple(): void
    {
        $lastActor = Actor::find(200);

        Log::info('Last actor', [$lastActor]);

        /*
            [2021-11-30 07:52:58] testing.INFO: Last actor [
        {"App\\Models\\Actor":
        {"id":200,"first_name":"THORA","last_name":"TEMPLE","created_at":"2006-02-15T04:34:33.000000Z","updated_at":"2006-02-15T04:34:33.000000Z"}
        }]

        */
        $this->assertSame('THORA', $lastActor->first_name);
        $this->assertSame('TEMPLE', $lastActor->last_name);
    }

    public function testLastPenelopeGuinessIsIn19Films(): void
    {
        $penelopeFilms = Actor::with('films')->first();

        Log::info('Penelope\'s films', [$penelopeFilms]);

        /*
            [2021-11-30 07:59:29] testing.INFO: Penelope's films [
        {"App\\Models\\Actor":
        {"id":1,"first_name":"PENELOPE","last_name":"GUINESS","created_at":"2006-02-15T04:34:33.000000Z","updated_at":"2006-02-15T04:34:33.000000Z",
        "films":[
        {"id":1,"title":"ACADEMY DINOSAUR","description":"A Epic Drama of a Feminist And a Mad Scientist who must Battle a Teacher in The Canadian Rockies","release_year":2006,"language_id":1,"original_language_id":null,"rental_duration":6,"rental_rate":"0.99","length":86,"replacement_cost":"20.99","rating":"PG","special_features":"Deleted Scenes,Behind the Scenes","created_at":"2006-02-15T05:03:42.000000Z","updated_at":"2006-02-15T05:03:42.000000Z","pivot":{"actor_id":1,"film_id":1}},
        {"id":23,"title":"ANACONDA CONFESSIONS","description":"A Lacklusture Display of a Dentist And a Dentist who must Fight a Girl in Australia","release_year":2006,"language_id":1,"original_language_id":null,"rental_duration":3,"rental_rate":"0.99","length":92,"replacement_cost":"9.99","rating":"R","special_features":"Trailers,Deleted Scenes","created_at":"2006-02-15T05:03:42.000000Z","updated_at":"2006-02-15T05:03:42.000000Z","pivot":{"actor_id":1,"film_id":23}},
        {"id":25,"title":"ANGELS LIFE","description":"A Thoughtful Display of a Woman And a Astronaut who must Battle a Robot in Berlin","release_year":2006,"language_id":1,"original_language_id":null,"rental_duration":3,"rental_rate":"2.99","length":74,"replacement_cost":"15.99","rating":"G","special_features":"Trailers","created_at":"2006-02-15T05:03:42.000000Z","updated_at":"2006-02-15T05:03:42.000000Z","pivot":{"actor_id":1,"film_id":25}},
        {"id":106,"title":"BULWORTH COMMANDMENTS","description":"A Amazing Display of a Mad Cow And a Pioneer who must Redeem a Sumo Wrestler in The Outback","release_year":2006,"language_id":1,"original_language_id":null,"rental_duration":4,"rental_rate":"2.99","length":61,"replacement_cost":"14.99","rating":"G","special_features":"Trailers","created_at":"2006-02-15T05:03:42.000000Z","updated_at":"2006-02-15T05:03:42.000000Z","pivot":{"actor_id":1,"film_id":106}},
        {"id":140,"title":"CHEAPER CLYDE","description":"A Emotional Character Study of a Pioneer And a Girl who must Discover a Dog in Ancient Japan","release_year":2006,"language_id":1,"original_language_id":null,"rental_duration":6,"rental_rate":"0.99","length":87,"replacement_cost":"23.99","rating":"G","special_features":"Trailers,Commentaries,Behind the Scenes","created_at":"2006-02-15T05:03:42.000000Z","updated_at":"2006-02-15T05:03:42.000000Z","pivot":{"actor_id":1,"film_id":140}},
        {"id":166,"title":"COLOR PHILADELPHIA","description":"A Thoughtful Panorama of a Car And a Crocodile who must Sink a Monkey in The Sahara Desert","release_year":2006,"language_id":1,"original_language_id":null,"rental_duration":6,"rental_rate":"2.99","length":149,"replacement_cost":"19.99","rating":"G","special_features":"Commentaries,Behind the Scenes","created_at":"2006-02-15T05:03:42.000000Z","updated_at":"2006-02-15T05:03:42.000000Z","pivot":{"actor_id":1,"film_id":166}},
        {"id":277,"title":"ELEPHANT TROJAN","description":"A Beautiful Panorama of a Lumberjack And a Forensic Psychologist who must Overcome a Frisbee in A Baloon","release_year":2006,"language_id":1,"original_language_id":null,"rental_duration":4,"rental_rate":"4.99","length":126,"replacement_cost":"24.99","rating":"PG-13","special_features":"Behind the Scenes","created_at":"2006-02-15T05:03:42.000000Z","updated_at":"2006-02-15T05:03:42.000000Z","pivot":{"actor_id":1,"film_id":277}},
        {"id":361,"title":"GLEAMING JAWBREAKER","description":"A Amazing Display of a Composer And a Forensic Psychologist who must Discover a Car in The Canadian Rockies","release_year":2006,"language_id":1,"original_language_id":null,"rental_duration":5,"rental_rate":"2.99","length":89,"replacement_cost":"25.99","rating":"NC-17","special_features":"Trailers,Commentaries","created_at":"2006-02-15T05:03:42.000000Z","updated_at":"2006-02-15T05:03:42.000000Z","pivot":{"actor_id":1,"film_id":361}},
        {"id":438,"title":"HUMAN GRAFFITI","description":"A Beautiful Reflection of a Womanizer And a Sumo Wrestler who must Chase a Database Administrator in The Gulf of Mexico","release_year":2006,"language_id":1,"original_language_id":null,"rental_duration":3,"rental_rate":"2.99","length":68,"replacement_cost":"22.99","rating":"NC-17","special_features":"Trailers,Behind the Scenes","created_at":"2006-02-15T05:03:42.000000Z","updated_at":"2006-02-15T05:03:42.000000Z","pivot":{"actor_id":1,"film_id":438}},
        {"id":499,"title":"KING EVOLUTION","description":"A Action-Packed Tale of a Boy And a Lumberjack who must Chase a Madman in A Baloon","release_year":2006,"language_id":1,"original_language_id":null,"rental_duration":3,"rental_rate":"4.99","length":184,"replacement_cost":"24.99","rating":"NC-17","special_features":"Trailers,Deleted Scenes,Behind the Scenes","created_at":"2006-02-15T05:03:42.000000Z","updated_at":"2006-02-15T05:03:42.000000Z","pivot":{"actor_id":1,"film_id":499}},
        {"id":506,"title":"LADY STAGE","description":"A Beautiful Character Study of a Woman And a Man who must Pursue a Explorer in A U-Boat","release_year":2006,"language_id":1,"original_language_id":null,"rental_duration":4,"rental_rate":"4.99","length":67,"replacement_cost":"14.99","rating":"PG","special_features":"Trailers,Deleted Scenes,Behind the Scenes","created_at":"2006-02-15T05:03:42.000000Z","updated_at":"2006-02-15T05:03:42.000000Z","pivot":{"actor_id":1,"film_id":506}},
        {"id":509,"title":"LANGUAGE COWBOY","description":"A Epic Yarn of a Cat And a Madman who must Vanquish a Dentist in An Abandoned Amusement Park","release_year":2006,"language_id":1,"original_language_id":null,"rental_duration":5,"rental_rate":"0.99","length":78,"replacement_cost":"26.99","rating":"NC-17","special_features":"Trailers,Deleted Scenes","created_at":"2006-02-15T05:03:42.000000Z","updated_at":"2006-02-15T05:03:42.000000Z","pivot":{"actor_id":1,"film_id":509}},
        {"id":605,"title":"MULHOLLAND BEAST","description":"A Awe-Inspiring Display of a Husband And a Squirrel who must Battle a Sumo Wrestler in A Jet Boat","release_year":2006,"language_id":1,"original_language_id":null,"rental_duration":7,"rental_rate":"2.99","length":157,"replacement_cost":"13.99","rating":"PG","special_features":"Trailers,Deleted Scenes,Behind the Scenes","created_at":"2006-02-15T05:03:42.000000Z","updated_at":"2006-02-15T05:03:42.000000Z","pivot":{"actor_id":1,"film_id":605}},
        {"id":635,"title":"OKLAHOMA JUMANJI","description":"A Thoughtful Drama of a Dentist And a Womanizer who must Meet a Husband in The Sahara Desert","release_year":2006,"language_id":1,"original_language_id":null,"rental_duration":7,"rental_rate":"0.99","length":58,"replacement_cost":"15.99","rating":"PG","special_features":"Behind the Scenes","created_at":"2006-02-15T05:03:42.000000Z","updated_at":"2006-02-15T05:03:42.000000Z","pivot":{"actor_id":1,"film_id":635}},
        {"id":749,"title":"RULES HUMAN","description":"A Beautiful Epistle of a Astronaut And a Student who must Confront a Monkey in An Abandoned Fun House","release_year":2006,"language_id":1,"original_language_id":null,"rental_duration":6,"rental_rate":"4.99","length":153,"replacement_cost":"19.99","rating":"R","special_features":"Deleted Scenes,Behind the Scenes","created_at":"2006-02-15T05:03:42.000000Z","updated_at":"2006-02-15T05:03:42.000000Z","pivot":{"actor_id":1,"film_id":749}},
        {"id":832,"title":"SPLASH GUMP","description":"A Taut Saga of a Crocodile And a Boat who must Conquer a Hunter in A Shark Tank","release_year":2006,"language_id":1,"original_language_id":null,"rental_duration":5,"rental_rate":"0.99","length":175,"replacement_cost":"16.99","rating":"PG","special_features":"Trailers,Commentaries,Deleted Scenes,Behind the Scenes","created_at":"2006-02-15T05:03:42.000000Z","updated_at":"2006-02-15T05:03:42.000000Z","pivot":{"actor_id":1,"film_id":832}},
        {"id":939,"title":"VERTIGO NORTHWEST","description":"A Unbelieveable Display of a Mad Scientist And a Mad Scientist who must Outgun a Mad Cow in Ancient Japan","release_year":2006,"language_id":1,"original_language_id":null,"rental_duration":4,"rental_rate":"2.99","length":90,"replacement_cost":"17.99","rating":"R","special_features":"Commentaries,Behind the Scenes","created_at":"2006-02-15T05:03:42.000000Z","updated_at":"2006-02-15T05:03:42.000000Z","pivot":{"actor_id":1,"film_id":939}},
        {"id":970,"title":"WESTWARD SEABISCUIT","description":"A Lacklusture Tale of a Butler And a Husband who must Face a Boy in Ancient China","release_year":2006,"language_id":1,"original_language_id":null,"rental_duration":7,"rental_rate":"0.99","length":52,"replacement_cost":"11.99","rating":"NC-17","special_features":"Commentaries,Deleted Scenes","created_at":"2006-02-15T05:03:42.000000Z","updated_at":"2006-02-15T05:03:42.000000Z","pivot":{"actor_id":1,"film_id":970}},
        {"id":980,"title":"WIZARD COLDBLOODED","description":"A Lacklusture Display of a Robot And a Girl who must Defeat a Sumo Wrestler in A MySQL Convention","release_year":2006,"language_id":1,"original_language_id":null,"rental_duration":4,"rental_rate":"4.99","length":75,"replacement_cost":"12.99","rating":"PG","special_features":"Commentaries,Deleted Scenes,Behind the Scenes","created_at":"2006-02-15T05:03:42.000000Z","updated_at":"2006-02-15T05:03:42.000000Z","pivot":{"actor_id":1,"film_id":980}}]}}]

       */
        $this->assertSame(19, $penelopeFilms->films->count());
        $this->assertSame('ACADEMY DINOSAUR', $penelopeFilms->films->first()->title);
        $this->assertSame('KING EVOLUTION', $penelopeFilms->films->find(499)->title);
        $this->assertSame('WIZARD COLDBLOODED', $penelopeFilms->films->find(980)->title);
    }
}

<?php

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use Wubs\NS\NSApi;
use Wubs\NS\Responses\Failure;
use Wubs\NS\Responses\Planner\Step;

/**
 * Created by PhpStorm.
 * User: bwubs
 * Date: 17/04/15
 * Time: 10:30
 */
class HowItShouldWorkTest extends TestCase
{

    /**
     * @test
     */
    public function get_stations_list()
    {
        date_default_timezone_set('Europe/Amsterdam');
        $api = new NSApi(getenv("NS_ACCOUNT_EMAIL"), getenv("NS_API_KEY"));

        $stations = $api->stations();

        $this->assertInstanceOf("Illuminate\\Support\\Collection", $stations);
        $this->assertInstanceOf("Wubs\\NS\\Responses\\Station", $stations[0]);
        $this->assertObjectHasAttribute("lat", $stations[0]);
        $this->assertObjectHasAttribute("long", $stations[0]);
        $this->assertObjectHasAttribute("name", $stations[0]);
        $this->assertNotNull($stations[0]->name);
    }

    /**
     * @test
     */
    public function get_failures_list()
    {
        $api = new NSApi(getenv("NS_ACCOUNT_EMAIL"), getenv("NS_API_KEY"));
        $failures = $api->failures("ut");

        $this->assertInstanceOf('Illuminate\Support\Collection', $failures->planned);
        $this->assertInstanceOf('Illuminate\Support\Collection', $failures->unplanned);

        foreach ($failures->planned as $plannedFailure)
        {
            $this->assertInstanceOf("Wubs\\NS\\Responses\\Failure", $plannedFailure);
        }

        /** @var Failure $unplannedFailure */
        foreach ($failures->unplanned as $unplannedFailure)
        {
            $this->assertInstanceOf("Wubs\\NS\\Responses\\Failure", $unplannedFailure);
            $this->assertNotNull($unplannedFailure->message);
            $this->assertNotNull($unplannedFailure->id);
        }

        write($failures->unplanned->toJson(JSON_PRETTY_PRINT), 'failures');
    }

    /**
     * @test
     */
    public function get_trip_advise()
    {
        $api = new NSApi(getenv("NS_ACCOUNT_EMAIL"), getenv("NS_API_KEY"));

        $advises = $api->advise(
            "Utrecht Centraal",
            "Wierden",
            Carbon::now(new DateTimeZone("Europe/Amsterdam")),
            true
        );

        $this->assertInstanceOf('Illuminate\Support\Collection', $advises);

        /** @var Option $advise */
        $advise = $advises->first();
        $this->assertInstanceOf('Wubs\NS\Responses\Planner\Advise', $advise);
        foreach ($advise as $key => $value)
        {
            $this->assertNotNull($value);
        }

        /** @var Step $part */
        $part = $advise->steps->first();
        $this->assertInstanceOf('Wubs\NS\Responses\Planner\Step', $part);

        $stop = $part->wayPoints->first();
        $this->assertInstanceOf('Wubs\NS\Responses\Planner\WayPoint', $stop);
    }

    /**
     * @test
     */
    public function dates_are_carbon_object()
    {
        $api = new NSApi(getenv("NS_ACCOUNT_EMAIL"), getenv("NS_API_KEY"));

        // Aalten -> Wierden
        $advises = $api->advise(
            "Aalten",
            "Wierden",
            Carbon::now(new DateTimeZone("Europe/Amsterdam")),
            true
        );

        foreach ($advises as $advise)
        {
            $this->assertInstanceOf(Carbon::class, $advise->actualArrivalTime);
            $this->assertInstanceOf(Carbon::class, $advise->actualDepartureTime);
            $this->assertInstanceOf(Carbon::class, $advise->plannedDepartureTime);
            $this->assertInstanceOf(Carbon::class, $advise->actualDepartureTime);
            foreach ($advise->steps as $step)
            {
                foreach ($step->wayPoints as $wayPoint)
                {
                    if ( ! is_null($wayPoint->time))
                    {
                        $this->assertInstanceOf(Carbon::class, $wayPoint->time);
                    }
                }
            }
        }

        write($advises->toJson(JSON_PRETTY_PRINT), "objects");
    }
}

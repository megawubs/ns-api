<?php
use Carbon\Carbon;
use Wubs\NS\NSApi;
use Wubs\NS\Responses\Failure;
use Wubs\NS\Responses\Planner\Option;
use Wubs\NS\Responses\Planner\Step;

/**
 * Created by PhpStorm.
 * User: bwubs
 * Date: 17/04/15
 * Time: 10:30
 */
class HowItShouldWorkTest extends PHPUnit_Framework_TestCase
{

    public function testGetStationsList()
    {
        $api = new NSApi(getenv("NS_ACCOUNT_EMAIL"), getenv("NS_API_KEY"));

        $stations = $api->stations();

        $this->assertInstanceOf("Illuminate\\Support\\Collection", $stations);
        $this->assertInstanceOf("Wubs\\NS\\Responses\\Station", $stations[0]);
        $this->assertObjectHasAttribute("lat", $stations[0]);
        $this->assertObjectHasAttribute("long", $stations[0]);
        $this->assertObjectHasAttribute("name", $stations[0]);
        $this->assertNotNull($stations[0]->getName());
    }

    /**
     *
     */
    public function testGetFailuresList()
    {
        $api = new NSApi(getenv("NS_ACCOUNT_EMAIL"), getenv("NS_API_KEY"));
        $failures = $api->failures("ut");

        $this->assertInstanceOf('Illuminate\Support\Collection', $failures->planned);
        $this->assertInstanceOf('Illuminate\Support\Collection', $failures->unplanned);

        foreach ($failures->planned as $plannedFailure) {
            $this->assertInstanceOf("Wubs\\NS\\Responses\\Failure", $plannedFailure);
        }

        /** @var Failure $unplannedFailure */
        foreach ($failures->unplanned as $unplannedFailure) {
            $this->assertInstanceOf("Wubs\\NS\\Responses\\Failure", $unplannedFailure);
            $this->assertNotNull($unplannedFailure->getMessage());
            $this->assertNotNull($unplannedFailure->getId());
        }
    }

    public function testGetTripAdvise()
    {
        $api = new NSApi(getenv("NS_ACCOUNT_EMAIL"), getenv("NS_API_KEY"));

        $advises = $api->tripAdvise(
            "Utrecht Centraal",
            "Wierden",
            Carbon::now(new DateTimeZone("Europe/Amsterdam"))->toIso8601String(),
            true
        );

        $this->assertInstanceOf('Illuminate\Support\Collection', $advises);

        /** @var Option $advise */
        $advise = $advises->first();
        $this->assertInstanceOf('Wubs\NS\Responses\Planner\Option', $advise);
        foreach ($advise as $key => $value) {
            $this->assertNotNull($value);
        }

        /** @var Step $part */
        $part = $advise->steps->first();
        $this->assertInstanceOf('Wubs\NS\Responses\Planner\Step', $part);

        $stop = $part->wayPoints->first();
        $this->assertInstanceOf('Wubs\NS\Responses\Planner\WayPoint', $stop);
    }
}

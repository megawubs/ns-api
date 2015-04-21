<?php
/**
 * Created by PhpStorm.
 * User: bwubs
 * Date: 21-04-15
 * Time: 10:35
 */

namespace Wubs\NS;


use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Wubs\NS\Contracts\Api;
use Wubs\NS\Responses\Failure;
use Wubs\NS\Responses\Failures;
use Wubs\NS\Responses\Planner\Option;
use Wubs\NS\Responses\Station;

class NSApi implements Api
{
    /**
     * @var
     */
    private $email;
    /**
     * @var
     */
    private $key;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $baseUrl = "http://webservices.ns.nl";

    private $auth = [];

    /**
     * @param $email
     * @param $key
     */
    public function __construct($email, $key)
    {
        $this->email = $email;
        $this->key = $key;
        $this->client = $this->getClient();
        $this->auth = [$this->email, $this->key];
    }


    /**
     * @return Collection|Station[]
     */
    public function stations()
    {
        $result = $this->client->get('/ns-api-stations', ['auth' => $this->auth]);
        return $this->toStations($result->xml());
    }

    /**
     * @param $station
     * @param string $actual
     * @param string $future
     * @return Failures
     */
    public function failures($station, $actual = "true", $future = "false")
    {
        $result = $this->client->get(
            '/ns-api-storingen',
            [
                'query' => ['station' => $station, 'actual' => $actual, 'unplanned' => $future],
                'auth' => $this->auth
            ]
        );
        return new Failures($result->xml());
    }

    /**
     * @param $fromStation
     * @param $toStation
     * @param $dateTime
     * @param $departure
     * @return Collection|Option[]
     */
    public function tripAdvise($fromStation, $toStation, $dateTime, $departure)
    {
        $query = compact("fromStation", "toStation", "dateTime", "departure");
        $result = $this->client->get(
            '/ns-api-treinplanner',
            [
                'query' => $query,
                'auth' => $this->auth
            ]
        );

        return $this->toTravelOptions($result->xml());
    }

    private function getClient()
    {
        return new Client(
            [
                'base_url' => $this->baseUrl
            ]
        );
    }

    /**
     * @param $xml
     * @return Collection|Station[]
     */
    private function toStations($xml)
    {
        $stations = new Collection();
        foreach ($xml as $stationXmlObject) {
            $stations->push(Station::create($stationXmlObject));
        }
        return $stations;
    }

    private function toTravelOptions($xml)
    {
        $travelOptions = new Collection();
        foreach ($xml as $travelOptionXmlObject) {
            $travelOptions->push(Option::create($travelOptionXmlObject));
        }
        return $travelOptions;
    }
}
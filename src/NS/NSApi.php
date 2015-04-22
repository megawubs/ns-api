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
use Wubs\NS\Responses\Planner\Advise;
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
        return Failures::fromXML($result->xml());
    }

    /**
     * @param $fromStation
     * @param $toStation
     * @param $dateTime
     * @param $departure
     * @return Collection|Advise[]
     */
    public function advise($fromStation, $toStation, $dateTime, $departure)
    {
        $query = compact("fromStation", "toStation", "dateTime", "departure");
        $result = $this->client->get(
            '/ns-api-treinplanner',
            [
                'query' => $query,
                'auth' => $this->auth
            ]
        );
        write($result->xml(), 'xml');
        return $this->toAdvises($result->xml());
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
    private function toStations(\SimpleXMLElement $xml)
    {
        $stations = new Collection();
        foreach ($xml as $stationXmlObject) {
            $stations->push(Station::fromXML($stationXmlObject));
        }
        return $stations;
    }

    private function toAdvises(\SimpleXMLElement $xml)
    {
        $travelOptions = new Collection();
        foreach ($xml as $travelOptionXmlObject) {
            $travelOptions->push(Advise::fromXML($travelOptionXmlObject));
        }
        return $travelOptions;
    }
}
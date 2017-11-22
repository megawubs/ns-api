<?php
/**
 * Created by PhpStorm.
 * User: bwubs
 * Date: 21-04-15
 * Time: 10:35
 */

namespace Wubs\NS;


use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Psr\Http\Message\ResponseInterface;
use SimpleXMLElement;
use Wubs\NS\Contracts\Api;
use Wubs\NS\Responses\Failures;
use Wubs\NS\Responses\Planner\Advise;
use Wubs\NS\Responses\Station;

/**
 * Class NSApi
 *
 * @package Wubs\NS
 */
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

    /**
     * @var array
     */
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
     * @return \GuzzleHttp\Client
     */
    private function getClient()
    {
        return new Client(
            [
                'base_uri' => $this->baseUrl
            ]
        );
    }

    /**
     * @return Collection|Station[]
     */
    public function stations()
    {
        $result = $this->client->get('/ns-api-stations', ['auth' => $this->auth]);

        return $this->toStations($result);
    }

    /**
     * @param ResponseInterface $response
     *
     * @return Collection|Station[]
     */
    private function toStations(ResponseInterface $response)
    {
        $xml = new SimpleXMLElement($response->getBody()
                                             ->getContents());
        $stations = new Collection();
        foreach ($xml as $stationXmlObject)
        {
            $stations->push(Station::fromXML($stationXmlObject));
        }

        return $stations;
    }

    /**
     * @param        $station
     * @param string $actual
     * @param string $future
     *
     * @return Failures
     */
    public function failures($station, $actual = "true", $future = "false")
    {
        $result = $this->client->get(
            '/ns-api-storingen',
            [
                'query' => ['station' => $station, 'actual' => $actual, 'unplanned' => $future],
                'auth'  => $this->auth
            ]
        );

        $result = new SimpleXMLElement($result->getBody()
                                              ->getContents());

        return Failures::fromXML($result);
    }

    /**
     * @param $fromStation
     * @param $toStation
     * @param $dateTime
     * @param $departure
     *
     * @return Collection|Advise[]
     */
    public function advise($fromStation, $toStation, Carbon $dateTime, $departure)
    {
        $dateTime = $dateTime->toIso8601String();
        $query = compact("fromStation", "toStation", "dateTime", "departure");
        $result = $this->client->get(
            '/ns-api-treinplanner',
            [
                'query' => $query,
                'auth'  => $this->auth
            ]
        );

        return $this->toAdvises($result);
    }

    /**
     * @param ResponseInterface $response
     *
     * @return \Illuminate\Support\Collection
     */
    private function toAdvises(ResponseInterface $response)
    {
        $xml = new SimpleXMLElement($response->getBody()
                                             ->getContents());
        $travelOptions = new Collection();
        foreach ($xml as $travelOptionXmlObject)
        {
            $travelOptions->push(Advise::fromXML($travelOptionXmlObject));
        }

        return $travelOptions;
    }
}
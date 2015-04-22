<?php


namespace Wubs\NS\Responses\Planner;


use Carbon\Carbon;
use Wubs\NS\Contracts\Response;
use Wubs\NS\Responses\Time;

class WayPoint implements Response
{
    use Time;

    public $name;

    /**
     * @var Carbon
     */
    public $time;

    public $departureDelay;

    public $track;

    /**
     * @param $departureDelay
     * @param $name
     * @param Carbon $time
     * @param $track
     */
    private function __construct($departureDelay, $name, Carbon $time = null, $track)
    {
        $this->departureDelay = $departureDelay;
        $this->name = $name;
        $this->time = $time;
        $this->track = $track;
    }

    public static function fromXML(\SimpleXMLElement $xml)
    {
        return new static(
            (string)$xml->VertrekVertraging,
            (string)$xml->Naam,
            static::toCarbon((string)$xml->Tijd),
            (string)$xml->Spoor
        );
    }
}
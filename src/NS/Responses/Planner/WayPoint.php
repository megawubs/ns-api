<?php


namespace Wubs\NS\Responses\Planner;


use Carbon\Carbon;
use Wubs\NS\Contracts\Response;

class WayPoint implements Response
{
    public $name;

    public $time;

    public $departureDelay;

    public $track;

    private function __construct($departureDelay, $name, $time, $track)
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
            (string)$xml->Tijd,
            (string)$xml->Spoor
        );
    }
}
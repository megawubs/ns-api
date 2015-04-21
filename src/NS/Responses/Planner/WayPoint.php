<?php


namespace Wubs\NS\Responses\Planner;


use Carbon\Carbon;

class WayPoint
{
    public $name;

    public $time;

    public $departureDelay;

    public $track;

    public function __construct($stop)
    {
        $this->name = (string)$stop->Naam;
        $this->time = (string)$stop->Tijd;
        $this->departureDelay = (string)$stop->VertrekVertraging;
        $this->track = (string)$stop->Spoor;
    }
}
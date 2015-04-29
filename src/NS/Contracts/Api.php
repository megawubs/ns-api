<?php namespace Wubs\NS\Contracts;

use Carbon\Carbon;

interface Api
{

    public function stations();

    public function failures($station, $actual = true, $future = false);

    public function advise($fromStation, $toStation, Carbon $dateTime, $departure);

}
<?php


namespace Wubs\NS\Responses;


use Carbon\Carbon;
use DateTime;

trait Time
{

    /**
     * @param $date
     *
     * @return Carbon|string
     */
    protected static function toCarbon($date)
    {
        if ($date !== "")
        {
            return Carbon::createFromFormat(DateTime::ISO8601, $date);
        }

        return null;

    }

}
<?php


namespace Wubs\NS\Responses;


use Carbon\Carbon;
use Wubs\NS\Contracts\Failure as FailureInterface;
use Wubs\NS\Contracts\Response;

class Failure implements Response
{
    use Time;

    public $id;

    public $route;

    public $reason;

    public $message;

    public $date;

    private function __construct(Carbon $date, $id, $message, $reason, $route)
    {
        $this->date = $date;
        $this->id = $id;
        $this->message = $message;
        $this->reason = $reason;
        $this->route = $route;
    }

    public static function fromXML(\SimpleXMLElement $xml)
    {
        $date = static::toCarbon((string)$xml->Datum);
        return new static(
            $date,
            (string)$xml->Id,
            (string)$xml->Bericht,
            (string)$xml->Reden,
            (string)$xml->Traject

        );
    }
}
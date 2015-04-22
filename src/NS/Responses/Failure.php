<?php


namespace Wubs\NS\Responses;


use Carbon\Carbon;
use Wubs\NS\Contracts\Failure as FailureInterface;
use Wubs\NS\Contracts\Response;

class Failure implements Response
{
    public $id;

    public $route;

    public $reason;

    public $message;

    public $date;

    private function __construct($date, $id, $message, $reason, $route)
    {
        $this->date = $date;
        $this->id = $id;
        $this->message = $message;
        $this->reason = $reason;
        $this->route = $route;
    }

    public static function fromXML(\SimpleXMLElement $xml)
    {
        return new static(
            (string)$xml->Id,
            (string)$xml->Traject,
            (string)$xml->Reden,
            (string)$xml->Bericht,
            (string)$xml->Datum
        );
    }
}
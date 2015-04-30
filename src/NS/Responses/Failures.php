<?php


namespace Wubs\NS\Responses;


use Illuminate\Support\Collection;
use Wubs\NS\Contracts\Response;

class Failures implements Response
{

    public $planned;

    public $unplanned;

    private function __construct($planned, $unplanned)
    {
        $this->planned = $planned;
        $this->unplanned = $unplanned;
    }

    public static function fromXML(\SimpleXMLElement $xml)
    {
        write($xml->Ongepland, "failures_xml");
        $planned = static::toFailures($xml->Gepland);
        $unplanned = static::toFailures($xml->Ongepland);
        return new static($planned, $unplanned);
    }

    private static function toFailures($failureObjects)
    {
        $failures = new Collection();
        foreach ($failureObjects->Storing as $failure) {
            $failures->push(Failure::fromXML($failure));
        }
        return $failures;
    }

    public function toJson()
    {
        return json_encode($this);
    }

    public function __toString()
    {
        return $this->toJson();
    }
}
<?php


namespace Wubs\NS\Responses;


use Illuminate\Support\Collection;
use SimpleXMLElement;
use Wubs\NS\Contracts\Response;

/**
 * Class Failures
 *
 * @package Wubs\NS\Responses
 */
class Failures implements Response
{

    /**
     * @var
     */
    public $planned;

    /**
     * @var
     */
    public $unplanned;

    /**
     * Failures constructor.
     *
     * @param $planned
     * @param $unplanned
     */
    private function __construct($planned, $unplanned)
    {
        $this->planned = $planned;
        $this->unplanned = $unplanned;
    }

    /**
     * @param \SimpleXMLElement $xml
     *
     * @return static
     */
    public static function fromXML(SimpleXMLElement $xml)
    {
        $planned = static::toFailures($xml->Gepland);
        $unplanned = static::toFailures($xml->Ongepland);

        return new static($planned, $unplanned);
    }

    /**
     * @param $failureObjects
     *
     * @return \Illuminate\Support\Collection
     */
    private static function toFailures($failureObjects)
    {
        $failures = new Collection();
        foreach ($failureObjects->Storing as $failure)
        {
            $failures->push(Failure::fromXML($failure));
        }

        return $failures;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * @return string
     */
    public function toJson()
    {
        return json_encode($this);
    }
}
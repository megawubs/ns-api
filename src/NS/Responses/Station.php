<?php


namespace Wubs\NS\Responses;


class Station
{

    public $name;

    public $code;

    public $country;

    public $lat;

    public $long;

    public $alias;

    function __construct($alias, $code, $country, $lat, $long, $name)
    {
        $this->alias = ($alias == "true") ? true : false;
        $this->code = $code;
        $this->country = $country;
        $this->lat = $lat;
        $this->long = $long;
        $this->name = $name;
    }


    /**
     * @param \SimpleXMLElement $xml
     *
     * @return Station
     */
    public static function fromXML(\SimpleXMLElement $xml)
    {

        return new static(
            (string)$xml->alias,
            (string)$xml->code,
            (string)$xml->country,
            (string)$xml->lat,
            (string)$xml->long,
            (string)$xml->name
        );
    }
}
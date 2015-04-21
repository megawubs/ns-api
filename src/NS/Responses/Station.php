<?php


namespace Wubs\NS\Responses;


use Illuminate\Support\Str;
use Wubs\NS\Contracts\Station as StationInterface;

class Station extends Response implements StationInterface
{
    protected $name;

    protected $code;

    protected $country;

    protected $lat;

    protected $long;

    protected $alias;

    /**
     * @param $xmlObject
     * @return Station
     */
    public static function create($xmlObject)
    {
        return new static($xmlObject);
    }

    public function getName()
    {
        return $this->name;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function getLatitude()
    {
        return $this->lat;
    }

    public function getLongitude()
    {
        return $this->long;
    }

    public function isAlias()
    {
        return $this->alias;
    }
}
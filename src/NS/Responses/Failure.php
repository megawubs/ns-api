<?php


namespace Wubs\NS\Responses;


use Carbon\Carbon;
use Wubs\NS\Contracts\Failure as FailureInterface;

class Failure extends Response implements FailureInterface
{
    protected $id;

    protected $traject;

    protected $reden;

    protected $bericht;

    protected $datum;

    public static function create($xml)
    {
        return new static($xml);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getRoute()
    {
        return $this->traject;
    }

    public function getReason()
    {
        return $this->reden;
    }

    public function getMessage()
    {
        return $this->bericht;
    }

    public function getDate()
    {
        return Carbon::createFromTimestampUTC($this->datum);
    }
}
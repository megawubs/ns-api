<?php


namespace Wubs\NS\Responses;


use Illuminate\Support\Collection;

class Failures extends Response
{

    public $planned;

    public $unplanned;

    public function __construct($xml)
    {
        $this->planned = $this->toFailures($xml->Gepland);
        $this->unplanned = $this->toFailures($xml->Ongepland);
    }

    private function toFailures($failureObjects)
    {

        $failures = new Collection();
        foreach ($failureObjects->Storing as $failure) {
            $failures->push(Failure::create($failure));
        }
        return $failures;
    }
}
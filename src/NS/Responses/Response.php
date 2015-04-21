<?php


namespace Wubs\NS\Responses;


use Illuminate\Support\Str;

abstract class Response
{

    protected function __construct($xml)
    {
        $this->map($xml);
    }

    protected function map($data)
    {
        foreach ($data as $property => $value) {
            $property = Str::camel($property);
            if (property_exists($this, $property)) {
                $this->{$property} = $value;
            }
        }
    }

}
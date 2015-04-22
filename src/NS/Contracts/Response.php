<?php


namespace Wubs\NS\Contracts;


interface Response
{

    public static function fromXML(\SimpleXMLElement $xml);
}
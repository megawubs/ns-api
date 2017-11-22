<?php


namespace Wubs\NS\Contracts;


/**
 * Interface Response
 *
 * @package Wubs\NS\Contracts
 */
interface Response
{

    /**
     * @param \SimpleXMLElement $xml
     *
     * @return mixed
     */
    public static function fromXML(\SimpleXMLElement $xml);
}
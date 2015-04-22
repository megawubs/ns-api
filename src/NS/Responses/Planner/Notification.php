<?php


namespace Wubs\NS\Responses\Planner;


use Wubs\NS\Contracts\Response;

class Notification implements Response
{
    public $id;

    public $serious;

    public $text;

    private function __construct($id, $serious, $text)
    {
        $this->id = $id;
        $this->serious = $serious;
        $this->text = $text;
    }

    public static function fromXML(\SimpleXMLElement $xml)
    {
        return new static(
            (string)$xml->Id,
            (string)$xml->Ernstig,
            (string)$xml->Text
        );
    }
}
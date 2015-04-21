<?php


namespace Wubs\NS\Responses\Planner;


class Notification
{
    public $id;

    public $serious;

    public $text;

    public function __construct($notificationXml)
    {
        $this->id = (string)$notificationXml->Id;
        $this->serious = (string)$notificationXml->Ernstig;
        $this->text = (string)$notificationXml->Text;
    }
}
<?php


namespace Wubs\NS\Contracts;


interface Failure
{
    public static function create($xml);

    public function getId();

    public function getRoute();

    public function getReason();

    public function getMessage();

    public function getDate();
}
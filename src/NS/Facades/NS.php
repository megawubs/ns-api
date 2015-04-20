<?php

namespace Wubs\NS\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Created by PhpStorm.
 * User: bwubs
 * Date: 20-04-15
 * Time: 11:05
 */
class NS extends Facade
{

    protected static function getFacadeAccessor()
    {
        return "zip";
    }

}
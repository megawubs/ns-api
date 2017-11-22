<?php
/**
 * Created by PhpStorm.
 * User: bwubs
 * Date: 17/04/15
 * Time: 12:18
 */

namespace Wubs\NS\Exception;


class UnsuccessfulRequestException extends \Exception
{

    protected $message = "The request returned an error, please try again";
}
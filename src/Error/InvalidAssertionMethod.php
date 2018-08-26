<?php
/**
 * Created by PhpStorm.
 * User: drupsys
 * Date: 26/08/18
 * Time: 09:11
 */

namespace OneSpec\Error;

class InvalidAssertionMethod extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
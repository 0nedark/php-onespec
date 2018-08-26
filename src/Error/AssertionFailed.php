<?php

namespace OneSpec\Error;

use OneSpec\Result\Result;

class AssertionFailed extends \Exception
{

    /**
     * @var Result
     */
    private $result;

    public function __construct(Result $result)
    {
        parent::__construct("Assertion failed!");
        $this->result = $result;
    }

    /**
     * @return Result
     */
    public function getResult(): Result
    {
        return $this->result;
    }
}
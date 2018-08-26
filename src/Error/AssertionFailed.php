<?php

namespace OneSpec\Error;

use OneSpec\Result\Output;

class AssertionFailed extends \Exception
{

    /**
     * @var Output
     */
    private $result;

    public function __construct(Output $result)
    {
        parent::__construct("Assertion failed!");
        $this->result = $result;
    }

    /**
     * @return Output
     */
    public function getResult(): Output
    {
        return $this->result;
    }
}
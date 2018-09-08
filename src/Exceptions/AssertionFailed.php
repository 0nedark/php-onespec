<?php

namespace OneSpec\Exceptions;

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
    public function getOutput(): Output
    {
        return $this->result;
    }
}
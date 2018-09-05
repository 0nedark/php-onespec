<?php

namespace Xae3Oow5cahz9shahngu\Exceptions;

use Xae3Oow5cahz9shahngu\Result\Output;

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
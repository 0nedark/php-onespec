<?php

namespace OneSpec\Assertions;

use OneSpec\Predicates\Equality;
use OneSpec\Result\Status;

class BaseAssertion
{
    protected $actual;
    protected $positive;

    use Equality;

    public function __construct($value)
    {
        $this->actual = $value;
    }

    /**
     * @param bool $positive
     * @return BaseAssertion
     */
    public function setPositive(bool $positive)
    {
        $this->positive = $positive;
        return $this;
    }

    protected function getStatus(bool $passed)
    {
        return $passed ? Status::SUCCESS : Status::FAILURE;
    }

    protected function hasAssertionFailed(bool $passed)
    {
        return (!$this->positive && !$passed) || ($this->positive && $passed);
    }
}
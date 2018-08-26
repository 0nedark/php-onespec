<?php

namespace OneSpec\Assertion;

use OneSpec\Check\Equality;
use OneSpec\Result\Status;

class BaseAssertion
{
    protected $value;
    protected $positive;

    use Equality;

    public function __construct($value)
    {
        $this->value = $value;
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
        return $passed ? Status::PASSED : Status::FAILED;
    }

    protected function hasAssertionFailed(bool $passed)
    {
        return (!$this->positive && !$passed) || ($this->positive && $passed);
    }
}
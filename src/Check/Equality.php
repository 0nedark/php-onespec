<?php

namespace OneSpec\Check;

use OneSpec\Result\Result;

trait Equality
{
    public function beEqualTo(array $arguments): Result
    {
        $passed = $this->hasAssertionFailed($this->value == $arguments[0]);
        $positive = $this->positive ? 'get' : 'not get';
        return new Result(
            $this->getStatus($passed),
            "Expected to ${positive} :expected but received :actual",
            ["expected" => $arguments[0], $this->value]
        );
    }

    public function beIdenticalTo(array $arguments): Result
    {
        $passed = $this->hasAssertionFailed($this->value === $arguments[0]);
        $positive = $this->positive ? 'be' : 'not be';
        return new Result(
            $this->getStatus($passed),
            "Expected :expected to ${positive} identical to :actual",
            ["expected" => $arguments[0], $this->value]
        );
    }
}

<?php

namespace OneSpec\Check;

trait Comparator
{
    public function beGreaterThan(array $arguments): bool
    {
        return $this->value > $arguments[0];
    }

    public function beGreaterThanOrEqualTo(array $arguments): bool
    {
        return $this->value >= $arguments[0];
    }

    public function beLessThan(array $arguments): bool
    {
        return $this->value < $arguments[0];
    }

    public function beLessThanOrEqualTo(array $arguments): bool
    {
        return $this->value <= $arguments[0];
    }

    public function beExclusivelyBetween(array $arguments): bool
    {
        return $this->value > $arguments[0] && $this->value < $arguments[1];
    }

    public function beInclusivelyBetween(array $arguments): bool
    {
        return $this->value >= $arguments[0] && $this->value <= $arguments[1];
    }
}
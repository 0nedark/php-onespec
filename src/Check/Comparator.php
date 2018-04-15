<?php

namespace OneSpec\Check;

trait Comparator
{
    public function beGreaterThan(array $arguments): array
    {
        $passed = $this->value > $arguments[0];
        return [$passed, "", $arguments[0], $this->value];
    }

    public function beGreaterThanOrEqualTo(array $arguments): array
    {
        $passed = $this->value >= $arguments[0];
        return [$passed, "", $arguments[0], $this->value];
    }

    public function beLessThan(array $arguments): array
    {
        $passed = $this->value < $arguments[0];
        return [$passed, "", $arguments[0], $this->value];
    }

    public function beLessThanOrEqualTo(array $arguments): array
    {
        $passed = $this->value <= $arguments[0];
        return [$passed, "", $arguments[0], $this->value];
    }

    public function beExclusivelyBetween(array $arguments): array
    {
        $passed = $this->value > $arguments[0] && $this->value < $arguments[1];
        return [$passed, "", "min($arguments[0]), max($arguments[1])", $this->value];
    }

    public function beInclusivelyBetween(array $arguments): array
    {
        $passed = $this->value >= $arguments[0] && $this->value <= $arguments[1];
        return [$passed, "", "min($arguments[0]), max($arguments[1])", $this->value];
    }
}
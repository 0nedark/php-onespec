<?php

namespace OneSpec\Check;

trait Equality
{
    public function beEqualTo(array $arguments): array
    {
        $passed = $this->value == $arguments[0];
        return [$passed, "Input does not match provided value", $arguments[0], $this->value];
    }

    public function beIdenticalTo(array $arguments): array
    {
        $passed = $this->value === $arguments[0];
        return [$passed, "Input is not identical to provided value", $arguments[0], $this->value];
    }
}
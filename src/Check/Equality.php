<?php

namespace OneSpec\Check;

trait Equality
{
    public function beEqualTo(array $arguments): array
    {
        $passed = $this->value == $arguments[0];
        return [$passed, "", $arguments[0], $this->value];
    }

    public function beIdenticalTo(array $arguments): array
    {
        $passed = $this->value === $arguments[0];
        return [$passed, "", $arguments[0], $this->value];
    }
}
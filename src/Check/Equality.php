<?php

namespace OneSpec\Check;

trait Equality
{
    public function beEqualTo(array $arguments): bool
    {
        return $this->value == $arguments[0];
    }

    public function beIdenticalTo(array $arguments): bool
    {
        return $this->value === $arguments[0];
    }
}
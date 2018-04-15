<?php

namespace OneSpec\Assertion;

use OneSpec\Check\Equality;

class BaseAssertion
{
    protected $value;

    use Equality;

    public function __construct($value)
    {
        $this->value = $value;
    }
}
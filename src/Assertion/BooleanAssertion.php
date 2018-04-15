<?php

namespace OneSpec\Assertion;

class BooleanAssertion extends BaseAssertion
{
    public function __construct(bool $value)
    {
        parent::__construct($value);
    }
}
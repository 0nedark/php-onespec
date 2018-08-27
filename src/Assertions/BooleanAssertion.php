<?php

namespace OneSpec\Assertions;

class BooleanAssertion extends BaseAssertion
{
    public function __construct(bool $value)
    {
        parent::__construct($value);
    }
}
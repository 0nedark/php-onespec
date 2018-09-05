<?php

namespace OneSpec\Assertions;

use OneSpec\Predicates\Compare;

class IntegerAssertion extends BaseAssertion
{
    use Compare;

    public function __construct(int $value)
    {
        parent::__construct($value);
    }
}
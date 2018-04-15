<?php

namespace OneSpec\Assertion;

use OneSpec\Check\Comparator;

class IntegerAssertion extends BaseAssertion
{
    use Comparator;

    public function __construct(int $value)
    {
        parent::__construct($value);
    }
}
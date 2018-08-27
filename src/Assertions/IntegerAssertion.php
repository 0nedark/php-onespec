<?php

namespace OneSpec\Assertions;

use OneSpec\Predicates\Comparator;

class IntegerAssertion extends BaseAssertion
{
    use Comparator;

    public function __construct(int $value)
    {
        parent::__construct($value);
    }
}
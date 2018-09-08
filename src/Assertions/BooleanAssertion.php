<?php

namespace OneSpec\Assertions;

use OneSpec\Predicates\Boolean;

class BooleanAssertion extends BaseAssertion
{
    use Boolean;

    public function __construct(bool $value)
    {
        parent::__construct($value);
    }
}
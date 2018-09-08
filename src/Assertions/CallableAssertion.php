<?php

namespace OneSpec\Assertions;

use OneSpec\Predicates\Throwing;

class CallableAssertion extends BaseAssertion
{
    use Throwing;

    public function __construct(callable $value)
    {
        parent::__construct($value);
    }
}
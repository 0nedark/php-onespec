<?php

namespace OneSpec\Assertions;

class CallableAssertion extends BaseAssertion
{
    public function __construct(callable $value)
    {
        parent::__construct($value);
    }
}
<?php

namespace OneSpec\Assertions;

class StringAssertion extends BaseAssertion
{
    public function __construct(string $value)
    {
        parent::__construct($value);
    }
}
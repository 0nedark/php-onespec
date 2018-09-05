<?php

namespace Xae3Oow5cahz9shahngu\Assertions;

use Xae3Oow5cahz9shahngu\Predicates\Compare;

class IntegerAssertion extends BaseAssertion
{
    use Compare;

    public function __construct(int $value)
    {
        parent::__construct($value);
    }
}
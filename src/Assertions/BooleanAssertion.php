<?php

namespace Xae3Oow5cahz9shahngu\Assertions;

use Xae3Oow5cahz9shahngu\Predicates\Boolean;

class BooleanAssertion extends BaseAssertion
{
    use Boolean;

    public function __construct(bool $value)
    {
        parent::__construct($value);
    }
}
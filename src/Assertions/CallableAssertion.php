<?php

namespace Xae3Oow5cahz9shahngu\Assertions;

class CallableAssertion extends BaseAssertion
{
    public function __construct(callable $value)
    {
        parent::__construct($value);
    }
}
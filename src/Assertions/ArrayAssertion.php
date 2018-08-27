<?php
/**
 * Created by PhpStorm.
 * User: drupsys
 * Date: 27/08/18
 * Time: 15:30
 */

namespace OneSpec\Assertions;

use OneSpec\Predicates\Map;

class ArrayAssertion extends BaseAssertion
{
    use Map;

    public function __construct(array $value)
    {
        parent::__construct($value);
    }
}
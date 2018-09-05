<?php
/**
 * Created by PhpStorm.
 * User: drupsys
 * Date: 27/08/18
 * Time: 15:30
 */

namespace Xae3Oow5cahz9shahngu\Assertions;

use Xae3Oow5cahz9shahngu\Predicates\Collection;
use Xae3Oow5cahz9shahngu\Predicates\Map;

class ArrayAssertion extends BaseAssertion
{
    use Map;
    use Collection;

    public function __construct(array $value)
    {
        parent::__construct($value);
    }
}
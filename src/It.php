<?php
/**
 * Created by PhpStorm.
 * User: drupsys
 * Date: 27/08/18
 * Time: 10:48
 */

namespace Xae3Oow5cahz9shahngu;

class It
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var callable
     */
    private $test;

    public function __construct(string $name, callable $test)
    {
        $this->name = $name;
        $this->test = $test;
    }

    /**
     * @return callable
     */
    public function getTest(): callable
    {
        return $this->test;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
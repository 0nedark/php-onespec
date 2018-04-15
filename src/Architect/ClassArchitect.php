<?php
/**
 * Created by PhpStorm.
 * User: dovydas
 * Date: 14/04/18
 * Time: 23:41
 */

namespace OneSpec\Architect;

use ReflectionClass;

class ClassArchitect
{
    private $args;
    private $reflection;

    public function __construct(string $class)
    {
        $this->reflection = new ReflectionClass($class);
    }

    public function beConstructedWith(...$args)
    {
        $this->args = empty($args) ? null : $args;
    }

    public function build()
    {
        return $this->reflection->newInstanceArgs($this->args);
    }

    public function __call($name, $arguments)
    {
        $obj = $this->build();
        $method = $this->reflection->getMethod($name);
        $method->setAccessible(true);

        return $method->invokeArgs($obj, $arguments);
    }
}
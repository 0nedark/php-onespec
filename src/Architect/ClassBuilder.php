<?php
/**
 * Created by PhpStorm.
 * User: dovydas
 * Date: 14/04/18
 * Time: 23:41
 */

namespace OneSpec\Architect;

use OneSpec\Architect\Error\ConstructorException;
use ReflectionClass;

class ClassBuilder
{
    private $args;
    private $reflection;
    private $object;

    public function __construct(string $class)
    {
        $this->reflection = new ReflectionClass($class);
    }

    public function beConstructedWith(...$args)
    {
        if (isset($this->object)) {
            throw new ConstructorException();
        }

        $this->args = empty($args) ? null : $args;
    }

    public function build()
    {
        if (isset($this->object)) {
            return $this->object;
        }

        if (count($this->args) > 0) {
            $this->object = $this->reflection->newInstanceArgs($this->args);
        } else {
            $class = $this->reflection->getName();
            $this->object = new $class;
        }

        return $this->object;
    }

    public function reset()
    {
        unset($this->object);
    }

    public function __call($name, $arguments)
    {
        $object = $this->build();
        $method = $this->reflection->getMethod($name);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $arguments);
    }
}
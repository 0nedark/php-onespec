<?php
/**
 * Created by PhpStorm.
 * User: dovydas
 * Date: 14/04/18
 * Time: 23:41
 */

namespace Xae3Oow5cahz9shahngu\Architect;

use Xae3Oow5cahz9shahngu\Architect\Error\ConstructorException;
use ReflectionClass;

class ClassBuilder
{
    private $args = [];
    private $object;
    private $function;
    private $class;

    public function __construct(string $class)
    {
        $this->class = $class;
    }

    public function beConstructed(...$args)
    {
        if (isset($this->object)) {
            throw new ConstructorException();
        }

        $this->args = empty($args) ? null : $args;
    }

    public function beConstructedThrough(string $function, ...$args)
    {
        if (isset($this->object)) {
            throw new ConstructorException();
        }

        $this->args = empty($args) ? null : $args;
        $this->function = $function;
    }

    public function build()
    {
        if (isset($this->object)) {
            return $this->object;
        }

        if (isset($this->function)) {
            $this->object = call_user_func($this->class . '::' . $this->function, ...$this->args);
        } elseif (count($this->args) > 0) {
            $reflection = new ReflectionClass($this->class);
            $this->object = $reflection->newInstanceArgs($this->args);
        } else {
            $reflection = new ReflectionClass($this->class);
            $class = $reflection->getName();
            $this->object = new $class;
        }

        return $this->object;
    }

    public function reset()
    {
        $this->args = [];
        unset($this->object, $this->function);
    }

    public function __call($name, $arguments)
    {
        $object = $this->build();
        $reflection = new ReflectionClass($this->class);
        $method = $reflection->getMethod($name);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $arguments);
    }
}
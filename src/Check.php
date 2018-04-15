<?php

namespace OneSpec;

use OneSpec\Architect\ClassBuilder;
use OneSpec\Assertion\BooleanAssertion;
use OneSpec\Assertion\BaseAssertion;
use OneSpec\Assertion\IntegerAssertion;
use OneSpec\Assertion\ObjectAssertion;
use OneSpec\Assertion\StringAssertion;
use OneSpec\Error\AssertionException;
use Prophecy\Exception\Doubler\MethodNotFoundException;

class Check
{
    private $assertion;
    private $value;

    public function __construct($value)
    {
        $this->value = $value;

        if ($value instanceof ClassBuilder) {
            $object = $value->build();
            $this->assertion = new ObjectAssertion($object);
        } elseif (is_bool($value)) {
            $this->assertion = new BooleanAssertion($value);
        } elseif (is_string($value)) {
            $this->assertion = new StringAssertion($value);
        } elseif (is_numeric($value)) {
            $this->assertion = new IntegerAssertion($value);
        } elseif (is_array($value)) {

        } elseif (is_callable($value)) {

        } elseif (is_object($value)) {
            $this->assertion = new ObjectAssertion($value);
        } else {
            $this->assertion = new BaseAssertion($value);
        }
    }

    public function __call($name, $arguments)
    {
        [$positive, $method] = $this->getTestMethod($name);
        $passed = $this->assertion->$method($arguments);

        if ($this->hasAssertionFailed($positive, $passed)) {
            throw new AssertionException("$this->value:" . implode(", ", $arguments));
        }
    }

    private function getTestMethod(string $name)
    {
        $names = preg_split('/(?=[A-Z])/', $name);
        $this->isMethodWithoutTo($names[0], $name);
        $positive = $this->isMethodPositive($names[1]);
        $assertion = array_splice($names, $positive ? 1 : 2);
        return [$positive, implode("", $assertion)];
    }

    private function isMethodWithoutTo(string $word, string $method)
    {
        if ($word !== "to") {
            throw new MethodNotFoundException(
                "Assertion should start with 'to'",
                get_class($this->assertion),
                $method
            );
        }
    }

    private function isMethodPositive(string $word)
    {
        $negate = false;
        if ($word === "Not") {
            $negate = true;
        }

        return !$negate;
    }

    private function hasAssertionFailed($positive, $passed) {
        return ($positive && !$passed) || (!$positive && $passed);
    }
}
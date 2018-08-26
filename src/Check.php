<?php

namespace OneSpec;

use function Functional\concat;
use OneSpec\Architect\ClassBuilder;
use OneSpec\Assertion\BooleanAssertion;
use OneSpec\Assertion\BaseAssertion;
use OneSpec\Assertion\IntegerAssertion;
use OneSpec\Assertion\ObjectAssertion;
use OneSpec\Assertion\StringAssertion;
use OneSpec\Error\AssertionFailed;
use OneSpec\Error\InvalidAssertionMethod;
use OneSpec\Result\Result;
use OneSpec\Result\Status;

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

    /**
     * @param $name
     * @param $arguments
     * @throws AssertionFailed
     * @throws InvalidAssertionMethod
     */
    public function __call($name, $arguments)
    {
        $names = preg_split('/(?=[A-Z])/', $name);
        $this->isMethodNameValid($names[0], $name);
        $positive = $this->isMethodPositive($names[1]);
        $method = concat(...array_splice($names, $positive ? 1 : 2));

        $result = $this->assertion
            ->setPositive($positive)
            ->$method($arguments);
        $this->handleResult($result);
    }

    /**
     * @param string $word
     * @param string $method
     * @throws InvalidAssertionMethod
     */
    private function isMethodNameValid(string $word, string $method)
    {
        if ($word !== "to") {
            throw new InvalidAssertionMethod(
                "Assertion should start with 'to', attempted to call ${method} on " . get_class($this->assertion)
            );
        }
    }

    private function isMethodPositive(string $word)
    {
        $positive = true;
        if ($word === "Not") {
            $positive = false;
        }

        return $positive;
    }

    /**
     * @param Result $result
     * @throws AssertionFailed
     */
    private function handleResult(Result $result)
    {
        if ($result->getStatus() === Status::FAILED) {
            throw new AssertionFailed($result);
        }
    }
}
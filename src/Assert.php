<?php

namespace Xae3Oow5cahz9shahngu;

use function Functional\concat;
use Xae3Oow5cahz9shahngu\Architect\ClassBuilder;
use Xae3Oow5cahz9shahngu\Assertions\ArrayAssertion;
use Xae3Oow5cahz9shahngu\Assertions\BooleanAssertion;
use Xae3Oow5cahz9shahngu\Assertions\BaseAssertion;
use Xae3Oow5cahz9shahngu\Assertions\CallableAssertion;
use Xae3Oow5cahz9shahngu\Assertions\IntegerAssertion;
use Xae3Oow5cahz9shahngu\Assertions\ObjectAssertion;
use Xae3Oow5cahz9shahngu\Assertions\StringAssertion;
use Xae3Oow5cahz9shahngu\Exceptions\AssertionFailed;
use Xae3Oow5cahz9shahngu\Exceptions\InvalidAssertionMethod;
use Xae3Oow5cahz9shahngu\Result\Output;
use Xae3Oow5cahz9shahngu\Result\Status;

class Assert
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
            $this->assertion = new ArrayAssertion($value);
        } elseif (is_callable($value)) {
            $this->assertion = new CallableAssertion($value);
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
            $message = "Assertion ${method} not found on " . get_class($this->assertion);
            throw new InvalidAssertionMethod($message);
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
     * @param Output $result
     * @throws AssertionFailed
     */
    private function handleResult(Output $result)
    {
        if ($result->getStatus() === Status::FAILURE) {
            throw new AssertionFailed($result);
        }
    }
}
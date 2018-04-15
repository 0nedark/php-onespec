<?php

namespace OneSpec\Error;

class AssertionException extends \Exception
{
    private $expected;
    private $actual;
    private $positive;

    public function __construct(string $message, string $expected, bool $positive, string $actual)
    {
        parent::__construct($message);
        $this->expected = $expected;
        $this->actual = $actual;
        $this->positive = $positive;
    }

    public function getExpected(): string
    {
        return $this->expected;
    }

    public function getActual(): string
    {
        return $this->actual;
    }

    public function isPositive(): bool
    {
        return $this->positive;
    }
}
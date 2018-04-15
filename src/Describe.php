<?php

namespace OneSpec;

use OneSpec\Architect\ClassArchitect;

class Describe
{
    private $class;
    private $before;
    private $after;
    private $beforeAll;
    private $afterAll;

    private function __construct(
        string $class,
        callable $before = null,
        callable $after = null,
        callable $beforeAll = null,
        callable $afterAll = null
    ) {
        $this->class = $class;
        $this->before = isset($this->before)
            ? $before : function () {};
        $this->after = isset($this->after)
            ? $after : function () {};
        $this->beforeAll = isset($this->beforeAll)
            ? $beforeAll : function () {};
        $this->afterAll = isset($this->afterAll)
            ? $this->afterAll : function () {};
    }

    public function group(string $name, callable $group)
    {
        ($this->beforeAll)();
        $group(new Describe(
            $this->class,
            $this->before,
            $this->after,
            $this->beforeAll,
            $this->afterAll
        ));
        ($this->afterAll)();
    }

    public function test(string $name, callable $tests)
    {
        $architect = new ClassArchitect($this->class);
        ($this->before)($architect);
        $tests(function ($actual) {
            return new Check($actual);
        }, $architect);
        ($this->after)($architect);
    }

    public function setBefore(callable $before)
    {
        $this->before = $before;
    }

    public function setAfter(callable $after)
    {
        $this->after = $after;
    }

    public function setBeforeAll(callable $beforeAll)
    {
        $this->beforeAll = $beforeAll;
    }

    public function setAfterAll(callable $afterAll)
    {
        $this->afterAll = $afterAll;
    }

    public static function class(string $class)
    {
        return new Describe($class);
    }
}
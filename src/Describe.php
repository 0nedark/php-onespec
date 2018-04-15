<?php

namespace OneSpec;

use OneSpec\Architect\ClassBuilder;

class Describe
{
    private $class;
    private $prevBeforeClosures;
    private $prevAfterClosures;
    private $beforeClosure;
    private $afterClosure;

    private function __construct(
        ClassBuilder $class,
        array $before = [],
        array $after = []
    ) {
        $this->class = $class;
        $this->prevBeforeClosures = $before;
        $this->prevAfterClosures = $after;
        $this->beforeClosure = function () {};
        $this->afterClosure = function () {};
    }

    public function group(string $name, callable $group)
    {
        $group(new Describe(
            $this->class,
            $this->getBeforeClosures(),
            $this->getAfterClosures()
        ));
    }

    public function test(string $name, callable $tests)
    {
        $this->callBeforeClosures();
        $tests(function ($actual) {
            return new Check($actual);
        }, $this->class);
        $this->callAfterClosures();
    }

    public function before(callable $before)
    {
        $this->beforeClosure = $before;
    }

    public function after(callable $after)
    {
        $this->afterClosure = $after;
    }

    private function getBeforeClosures(): array
    {
        return array_merge(
            $this->prevBeforeClosures,
            [$this->beforeClosure]
        );
    }

    private function callBeforeClosures()
    {
        $this->class->reset();
        foreach ($this->prevBeforeClosures as $before) {
            $before($this->class);
        }
        ($this->beforeClosure)($this->class);
    }

    private function getAfterClosures(): array
    {
        return array_merge(
            $this->prevAfterClosures,
            [$this->afterClosure]
        );
    }

    private function callAfterClosures()
    {
        foreach ($this->prevAfterClosures as $after) {
            $after($this->class);
        }
        ($this->afterClosure)($this->class);
    }

    public static function class(string $class)
    {
        return new Describe(new ClassBuilder($class));
    }
}
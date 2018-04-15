<?php

namespace OneSpec;

use OneSpec\Architect\ClassBuilder;
use OneSpec\Error\AssertionException;

class Describe
{
    private $class;
    private $prevBeforeClosures;
    private $prevAfterClosures;
    private $beforeClosure;
    private $afterClosure;
    private $output;

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
        $this->output = (object)[];
    }

    public function group(string $name, callable $group)
    {
        $desc = new Describe(
            $this->class,
            $this->getBeforeClosures(),
            $this->getAfterClosures()
        );

        $group($desc);

        $key = $this->getUniqueKey($name);
        $this->output->$key = $desc->getOutput();
    }

    public function test(string $name, callable $tests)
    {
        $result = (object)[
            "status" => "PASSED"
        ];

        try {
            $this->callBeforeClosures();
            $tests(function ($actual) {
                return new Check($actual);
            }, $this->class);
            $this->callAfterClosures();
        } catch (\Exception $e) {
            if ($e instanceof AssertionException) {
                $result->status = "FAILED";
                $result->message = $e->getMessage();
                $result->expected = $e->getExpected();
                $result->positive = (int)$e->isPositive();
                $result->actual = $e->getActual();
            } else {
                $result->status = "ERROR";
                $result->message = $e->getMessage();
            }
        }

        $key = $this->getUniqueKey($name);
        $this->output->$key = $result;
    }

    public function before(callable $before)
    {
        $this->beforeClosure = $before;
    }

    public function after(callable $after)
    {
        $this->afterClosure = $after;
    }

    public function getOutput(): \stdClass
    {
        return $this->output;
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

    private function getUniqueKey(string $name): string
    {
        do {
            $key = bin2hex(openssl_random_pseudo_bytes(3)) . ": $name";
        } while (property_exists($this->output, $key));
        return $key;
    }

    public static function class(string $class)
    {
        return new Describe(new ClassBuilder($class));
    }
}
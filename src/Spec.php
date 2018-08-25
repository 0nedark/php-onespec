<?php

namespace OneSpec;

use OneSpec\Architect\ClassBuilder;
use OneSpec\Error\AssertionException;
use OneSpec\Result\Result;
use OneSpec\Result\Status;

class Spec
{
    private $classBuilder;
    private $prevBeforeClosures;
    private $prevAfterClosures;
    private $beforeClosure;
    private $afterClosure;
    private $output;

    private function __construct(
        ClassBuilder $classBuilder,
        array $before = [],
        array $after = []
    ) {
        $this->classBuilder = $classBuilder;
        $this->prevBeforeClosures = $before;
        $this->prevAfterClosures = $after;
        $this->beforeClosure = function () {};
        $this->afterClosure = function () {};
        $this->output = (object)[];
    }

    public function describe(string $name, callable $describe)
    {
        $spec = new Spec(
            $this->classBuilder,
            $this->getBeforeClosures(),
            $this->getAfterClosures()
        );

        $describe($spec);

        $key = $this->getUniqueKey($name);
        $this->output->$key = $spec;
    }

    public function test(string $name, callable $tests)
    {
        $result = new Result();

        try {
            $this->callBeforeClosures();
            $tests(function ($actual) {
                return new Check($actual);
            }, $this->classBuilder);
            $this->callAfterClosures();
        } catch (\Exception $e) {
            if ($e instanceof AssertionException) {
                $result = new Result(Status::FAILED);
                $result->setFailureDetails(
                    $e->getMessage(),
                    $e->getExpected(),
                    (int)$e->isPositive(),
                    $e->getActual()
                );
            } else {
                $result = new Result(Status::ERROR);
                $result->setErrorDetails($e->getMessage(), $e->getFile(), $e->getLine());
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

    public function printResults(PrintInterface $print, int $depth = 0)
    {
        foreach ($this->output as $key => $value) {
            if ($value instanceof Spec) {
                $print->title($key, $depth);
                $value->printResults($print, $depth + 1);
            } else {
                $print->result($key, $value, $depth);
            }
        }
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
        $this->classBuilder->reset();
        foreach ($this->prevBeforeClosures as $before) {
            $before($this->classBuilder);
        }
        ($this->beforeClosure)($this->classBuilder);
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
            $after($this->classBuilder);
        }
        ($this->afterClosure)($this->classBuilder);
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
        return new Spec(new ClassBuilder($class));
    }
}
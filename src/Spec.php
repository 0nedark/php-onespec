<?php

namespace OneSpec;

use OneSpec\Architect\ClassBuilder;
use OneSpec\Error\AssertionFailed;
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
        $result = new Result(Status::PASSED);
        try {
            $this->callBeforeClosures();
            $tests(function ($actual) {
                return new Check($actual);
            }, $this->classBuilder);
            $this->callAfterClosures();
        } catch (\Exception $e) {
            if ($e instanceof AssertionFailed) {
                $result = $e->getResult();
            } else {
                $result = new Result(
                    Status::EXCEPTION,
                    'An error was thrown during the test: (:error) -> (file :file) -> (line :line)',
                    ['error' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()]
                );
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

    public function printResults(PrintInterface $print, int $depth = 0, string $file = '')
    {
        if ($depth === 0) {
            $key = $this->getUniqueKey($file);
            [$id, $file] = explode(':', $key);
            $print->title($id, $file, $depth);
            $this->printResults($print, $depth + 1);
        } else {
            foreach ($this->output as $key => $value) {
                [$id, $name] = explode(':', $key);
                if ($value instanceof Spec) {
                    $print->title($id, $name, $depth);
                    $value->printResults($print, $depth + 1);
                } else {
                    $print->result($id, $name, $value, $depth);
                }
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
        $key = md5($name) . ": $name";
        if (property_exists($this->output, $key)) {
            throw new \Exception("Tests must have different names");
        }

        return $key;
    }

    public static function class(string $class)
    {
        return new Spec(new ClassBuilder($class));
    }
}
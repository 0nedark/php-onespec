<?php

namespace OneSpec;

use function Functional\each;
use OneSpec\Architect\ClassBuilder;
use OneSpec\Error\AssertionFailed;
use OneSpec\Result\Color;
use OneSpec\Result\Output;
use OneSpec\Result\Status;
use OneSpec\Result\Text;

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
        $this->output = [];
    }

    public function describe(string $name, callable $describe)
    {
        $spec = new Spec(
            $this->classBuilder,
            $this->getBeforeClosures(),
            $this->getAfterClosures()
        );

        $describe($spec);

        $key = $this->getUniqueKey('describe ' . $name);
        $this->output[$key] = $spec;
    }

    public function it(string $name, callable $tests)
    {
        $key = $this->getUniqueKey('it ' . $name);
        $this->output[$key] = function () use ($tests) {
            $this->callBeforeClosures();
            $tests(function ($actual) {
                return new Check($actual);
            }, $this->classBuilder);
            $this->callAfterClosures();
        };
    }

    public function before(callable $before)
    {
        $this->beforeClosure = $before;
    }

    public function after(callable $after)
    {
        $this->afterClosure = $after;
    }

    public function printFile(PrintInterface $print, string $file = '')
    {
        $key = $this->getUniqueKey($file);
        $print->title($this->getOutputFromKey($key), 0);
        $this->printResults($print, 1);
    }

    private function printResults(PrintInterface $print, int $depth)
    {
        each($this->output, $this->printResult($print, $depth));
    }

    private function printResult(PrintInterface $print, int $depth)
    {
        return function ($value, $key) use ($print, $depth) {
            if ($value instanceof Spec) {
                $print->title($this->getOutputFromKey($key), $depth);
                $value->printResults($print, $depth + 1);
            } elseif (is_callable($value)) {
                $output = $this->runTest($value);
                $print->result($this->getOutputFromKey($key, $output->getStatus()), $output, $depth);
            }
        };
    }

    private function runTest(callable $test): Output
    {
        try {
            $test();
        } catch (\Exception $e) {
            if ($e instanceof AssertionFailed) {
                return $e->getOutput();
            } else {
                return new Output(
                    Status::EXCEPTION,
                    new Text(
                        'An error was thrown during the test: :error in file :file on line :line',
                        Color::PRIMARY
                    ),
                    [
                        'error' => new Text($e->getMessage(), Color::EXCEPTION),
                        'file' => new Text($e->getFile(), Color::EXCEPTION),
                        'line' => new Text($e->getLine(), Color::EXCEPTION),
                    ]
                );
            }
        }

        return new Output(Status::SUCCESS, new Text('', Color::PRIMARY));
    }

    private function getOutputFromKey(string $key, string $status = Status::WARNING): Output
    {
        [$id, $name] = explode(':', $key);
        return new Output(
            $status,
            new Text(":id {$name}", Color::PRIMARY),
            ['id' => new Text("$id", $status, ['KEY'])]
        );
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

    /**
     * @param string $name
     * @return string
     * @throws \Exception
     */
    private function getUniqueKey(string $name): string
    {
        $key = md5($name) . ":$name";
        if (isset($this->output[$key])) {
            throw new \Exception("Tests must have different names");
        }

        return $key;
    }

    public static function class(string $class)
    {
        return new Spec(new ClassBuilder($class));
    }
}
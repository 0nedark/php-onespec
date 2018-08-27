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
    private static $keys = [];
    private $name;
    private $classBuilder;
    private $prevBeforeClosures;
    private $prevAfterClosures;
    private $beforeClosure;
    private $afterClosure;
    private $output;

    private function __construct(
        string $name,
        ClassBuilder $classBuilder,
        array $before = [],
        array $after = []
    ) {
        $this->name = $name;
        $this->classBuilder = $classBuilder;
        $this->prevBeforeClosures = $before;
        $this->prevAfterClosures = $after;
        $this->beforeClosure = function () {};
        $this->afterClosure = function () {};
        $this->output = [];
    }

    public function describe(string $title, callable $describe)
    {
        $name = 'describe ' . $title;
        $key = self::getUniqueKey($name);
        $this->output[$key] = new Spec(
            $name,
            $this->classBuilder,
            $this->getBeforeClosures(),
            $this->getAfterClosures()
        );

        $describe($this->output[$key]);
    }

    public function it(string $name, callable $tests)
    {
        $name = 'it ' . $name;
        $key = self::getUniqueKey($name);
        $this->output[$key] = new It(
            $name,
            function () use ($tests) {
                $this->callBeforeClosures();
                $tests(function ($actual) {
                    return new Check($actual);
                }, $this->classBuilder);
                $this->callAfterClosures();
            }
        );
    }

    public function before(callable $before)
    {
        $this->beforeClosure = $before;
    }

    public function after(callable $after)
    {
        $this->afterClosure = $after;
    }

    public function runSpecificTest(PrintInterface $print, string $id): bool
    {
        $key = self::getKey($this->name);
        $contains = strpos($key, $id) !== false || $this->name === $id;
        if ($contains || $this->findTestInSpec($id)) {
            $this->runSpecInFile($print);
            return true;
        }

        return false;
    }

    public function runSpecInFile(PrintInterface $print)
    {
        $key = self::getUniqueKey($this->name);
        $print->title($this->getOutputFromKey($key, $this->name), 0);
        $this->runTestsInSpec($print, 1);
    }

    private function findTestInSpec(string $id): bool
    {
        $found = false;
        foreach ($this->output as $key => $value) {
            $contains = strpos($key, $id) !== false || $value->getName() === $id;
            if ($contains || ($value instanceof Spec && $value->findTestInSpec($id))) {
                $this->output = [$key => $value];
                $found = true;
                break;
            }
        }

        return $found;
    }

    private function runTestsInSpec(PrintInterface $print, int $depth)
    {
        each($this->output, $this->runEntityOfASpec($print, $depth));
    }

    private function runEntityOfASpec(PrintInterface $print, int $depth)
    {
        return function ($value, $id) use ($print, $depth) {
            if ($value instanceof Spec) {
                $print->title($this->getOutputFromKey($id, $value->getName()), $depth);
                $value->runTestsInSpec($print, $depth + 1);
            } elseif ($value instanceof It) {
                $output = $this->runTest($value->getTest());
                $title = $this->getOutputFromKey($id, $value->getName(), $output->getStatus());
                $print->result($title, $output, $depth);
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

    private function getOutputFromKey(string $id, string $title, string $status = Status::WARNING): Output
    {
        return new Output(
            $status,
            new Text(":id {$title}", Color::PRIMARY),
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

    private static function getKey(string $name): string
    {
        return md5($name);
    }

    /**
     * @param string $name
     * @param bool $temp
     * @return string
     * @throws \Exception
     */
    private static function getUniqueKey(string $name, bool $temp = false): string
    {
        $key = self::getKey($name);
        if (self::$keys[$key] && !$temp) {
            throw new \Exception("Tests must have different names");
        } else {
            self::$keys[$key] = true;
        }

        return $key;
    }

    public static function class(string $title, string $class)
    {
        return new Spec('class ' . $title, new ClassBuilder($class));
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}

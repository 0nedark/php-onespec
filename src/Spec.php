<?php

namespace Xae3Oow5cahz9shahngu;

use function Functional\each;
use Xae3Oow5cahz9shahngu\Architect\ClassBuilder;
use Xae3Oow5cahz9shahngu\Exceptions\AssertionFailed;
use Xae3Oow5cahz9shahngu\Result\Color;
use Xae3Oow5cahz9shahngu\Result\Icon;
use Xae3Oow5cahz9shahngu\Result\Output;
use Xae3Oow5cahz9shahngu\Result\Status;
use Xae3Oow5cahz9shahngu\Result\Text;

class Spec
{
    private static $keys = [];

    private $hash;
    private $name;
    private $classBuilder;
    private $prevBeforeClosures;
    private $prevAfterClosures;
    private $beforeClosure;
    private $afterClosure;
    private $output;

    public function __construct(
        string $hash,
        string $name,
        ClassBuilder $classBuilder,
        array $before = [],
        array $after = []
    ) {
        $this->hash = $hash;
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
        $hash = self::getUniqueKey($this->hash . $name);
        $this->output[$hash] = new Spec(
            $hash,
            $name,
            $this->classBuilder,
            $this->getBeforeClosures(),
            $this->getAfterClosures()
        );

        $describe($this->output[$hash]);
    }

    public function it(string $name, callable $tests)
    {
        $name = 'it ' . $name;
        $key = self::getUniqueKey($this->hash . $name);
        $this->output[$key] = new It(
            $name,
            function () use ($tests) {
                $this->callBeforeClosures();
                $tests(function ($actual) {
                    return new Assert($actual);
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
        $key = self::getKey($this->name);
        $title = $this->getOutputFromKey($key, $this->name);
        $print->title($title, new Icon($title->getStatus()), 0);
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
                $title = $this->getOutputFromKey($id, $value->getName());
                $print->title($title, new Icon($title->getStatus()), $depth);
                $value->runTestsInSpec($print, $depth + 1);
            } elseif ($value instanceof It) {
                $output = $this->runTest($value->getTest());
                $title = $this->getOutputFromKey($id, $value->getName(), $output->getStatus());
                $print->result($title, new Icon($output->getStatus()), $output, $depth);
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

    private function getOutputFromKey(string $id, string $title, string $status = Status::NONE): Output
    {
        return new Output(
            $status,
            new Text(":id :pipe ${title}", Color::PRIMARY),
            [
                'icon' => new Icon($status),
                'id' => new Text("$id", $status, ['KEY']),
                'pipe' => new Text('|', Color::SECONDARY),
            ]
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

    public static function class(string $class): Spec
    {
        $hash = self::getUniqueKey('class ' . $class);
        $name = 'class ' . $class;
        return new Spec($hash, $name, new ClassBuilder($class));
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}

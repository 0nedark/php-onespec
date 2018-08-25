<?php
/**
 * Created by PhpStorm.
 * User: drupsys
 * Date: 25/02/12
 * Time: 15:30
 */

namespace OneSpec\Cli;

use function Functional\concat;
use function Functional\intersperse;
use function Functional\map;
use OneSpec\PrintInterface;
use OneSpec\Result\Result;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Terminal;

class Printer implements PrintInterface
{
    const INDENTATION = 2;

    /**
     * @var OutputInterface
     */
    private $io;
    private $width;

    public function __construct(SymfonyStyle $io)
    {
        $success = new OutputFormatterStyle('green', null);
        $failure = new OutputFormatterStyle('red', null);
        $error = new OutputFormatterStyle('magenta', null);
        $io->getFormatter()->setStyle('PASS', $success);
        $io->getFormatter()->setStyle('FAILURE', $failure);
        $io->getFormatter()->setStyle('ERROR', $error);
        $this->io = $io;
        $this->width = (new Terminal())->getWidth() - 2;
    }

    private function createLines(int $indentation, string $text, bool $first = false)
    {
        $width = $this->width - $indentation;
        $lines = str_split($text, $width);
        $padding = str_repeat(' ', $indentation);
        for ($i = $first ? 0 : 1; $i < count($lines); $i++) {
            $lines[$i] = $padding . $lines[$i];
        }

        return $lines;
    }

    function result(string $name, Result $result, int $depth)
    {
        $this->title($name, $depth, $result->getStatus());
    }

    function title(string $name, int $depth, string $status = 'COMMENT')
    {
        [$id, $test] = explode(': ', $name);
        $prefix = $id . ': ';
        $indentation = $depth * self::INDENTATION;

        $this->io->write(map($this->createLines($indentation, $prefix, true), function ($prefix) use ($status) {
            [$id] = explode(': ', $prefix);
            return "<${status}>" . $id . '</>: ';
        }));

        $this->io->writeln(map($this->createLines($indentation + strlen($prefix), $test), function ($line) {
            return '<comment>' . $line . '</comment>';
        }));
    }
}
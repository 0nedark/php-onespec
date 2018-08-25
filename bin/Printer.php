<?php
/**
 * Created by PhpStorm.
 * User: drupsys
 * Date: 25/02/12
 * Time: 15:30
 */

namespace OneSpec\Cli;

use function Functional\map;
use OneSpec\PrintInterface;
use OneSpec\Result\Result;
use OneSpec\Result\Status;
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
        $success = new OutputFormatterStyle('green', null, ['bold']);
        $failure = new OutputFormatterStyle('red', null, ['bold']);
        $error = new OutputFormatterStyle('magenta', null, ['bold']);
        $comment = new OutputFormatterStyle('yellow', null, ['bold']);
        $io->getFormatter()->setStyle('PASS', $success);
        $io->getFormatter()->setStyle('FAILURE', $failure);
        $io->getFormatter()->setStyle('ERROR', $error);
        $io->getFormatter()->setStyle('WARNING', $comment);
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

    function result(string $id, string $name, Result $result, int $depth)
    {
        $this->title($id, $name, $depth, $result->getStatus());
        $indentation = $depth * self::INDENTATION + strlen($id . ': ');

        switch ($result->getStatus()) {
            case Status::FAILED:
                $message = $result->getMessage();
                $this->io->writeln($this->createLines($indentation, $message, true));
                $expected = $result->getExpected();
                $actual = $result->getActual();
                if ($result->getPositive()) {
                    $message = "Expected to get <pass>${expected}</pass> but received <failure>${actual}</failure>";
                    $this->io->writeln($this->createLines($indentation + 2, $message, true));
                } else {
                    $message = "Expected to not get <pass>${expected}</pass> but received <failure>${actual}</failure>";
                    $this->io->writeln($this->createLines($indentation + 2, $message, true));
                }

                break;
            case Status::ERROR:
                $message = 'An error was thrown during a test -> ' . $result->getMessage() . ', in file ' . $result->getFile() . ' on line ' . $result->getLine();
                $this->io->writeln($this->createLines($indentation, $message, true));
                break;
        }
    }

    function title(string $id, string $name, int $depth, string $status = 'WARNING')
    {
        $indentation = $depth * self::INDENTATION;
        $this->io->write(map($this->createLines($indentation, $id, true), function ($id) use ($status) {
            return "<${status}>" . $id . "</${status}>:";
        }));

        $indentation += strlen($id . ': ');
        $this->io->writeln(map($this->createLines($indentation, $name) , function ($line) {
            return $line;
        }));
    }
}
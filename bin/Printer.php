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
        $io->getFormatter()->setStyle('PASSED', $success);
        $io->getFormatter()->setStyle('FAILED', $failure);
        $io->getFormatter()->setStyle('EXCEPTION', $error);
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
        $shortId = substr($id, 0, 4);
        if ($result->getStatus() !== 'PASSED') {
            $indentation = $depth * self::INDENTATION + strlen($shortId . ': ');
            $this->io->writeln($this->createLines($indentation, $result->getMessage(), true));
        }
    }

    function title(string $id, string $name, int $depth, string $status = 'WARNING')
    {
        $indentation = $depth * self::INDENTATION;
        $shortId = substr($id, 0, 4);
        $this->io->write(map($this->createLines($indentation, $shortId, true), function ($id) use ($status) {
            return "<${status}>" . $id . "</${status}>:";
        }));

        $indentation += strlen($shortId . ': ');
        $this->io->writeln(map($this->createLines($indentation, $name) , function ($line) {
            return $line;
        }));
    }
}
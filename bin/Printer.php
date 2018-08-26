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
use OneSpec\Result\Title;
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
        $warning = new OutputFormatterStyle('yellow', null, ['bold']);
        $io->getFormatter()->setStyle('success', $success);
        $io->getFormatter()->setStyle('failure', $failure);
        $io->getFormatter()->setStyle('warning', $warning);
        $io->getFormatter()->setStyle('exception', $error);
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

    function result(Title $title, Result $result, int $depth)
    {
        $this->title($title, $depth, $result->getStatus());
        if ($result->getStatus() !== 'PASSED') {
            $shortId = $title->getShortId()->getValue();
            $indentation = $depth * self::INDENTATION + strlen($shortId . ': ');
            $this->io->writeln($this->createLines($indentation, $result->getMessage()->getValue(), true));
        }
    }

    function title(Title $title, int $depth, string $status = 'WARNING')
    {
        $indentation = $depth * self::INDENTATION;
        $shortId = $title->getShortId()->getValue();
        $this->io->write(map($this->createLines($indentation, $shortId, true), function ($id) use ($status) {
            return "<${status}>" . $id . "</${status}>: ";
        }));

        $indentation += strlen($shortId . ': ');
        $this->io->writeln(map($this->createLines($indentation, $title->getName()->getValue()) , function ($line) {
            return $line;
        }));
    }
}
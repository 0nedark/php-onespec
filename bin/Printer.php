<?php
/**
 * Created by PhpStorm.
 * User: drupsys
 * Date: 25/02/12
 * Time: 15:30
 */

namespace OneSpec\Cli;

use function Functional\each;
use function Functional\reduce_left;
use OneSpec\PrintInterface;
use OneSpec\Result\Color;
use OneSpec\Result\Output;
use OneSpec\Result\Text;
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
        $primary = new OutputFormatterStyle('white', null);
        $io->getFormatter()->setStyle('primary', $primary);
        $secondary = new OutputFormatterStyle('yellow', null, ['bold']);
        $io->getFormatter()->setStyle('secondary', $secondary);

        $success = new OutputFormatterStyle('green', null, ['bold']);
        $io->getFormatter()->setStyle('success', $success);
        $failure = new OutputFormatterStyle('red', null, ['bold']);
        $io->getFormatter()->setStyle('failure', $failure);
        $warning = new OutputFormatterStyle('yellow', null, ['bold']);
        $io->getFormatter()->setStyle('warning', $warning);
        $error = new OutputFormatterStyle('magenta');
        $io->getFormatter()->setStyle('exception', $error);

        $this->io = $io;
        $this->width = (new Terminal())->getWidth() - 4;
    }

    private function isWordKey(string $word): bool
    {
        $found = preg_match('/^:[A-z0-9]+$/', $word,$matches);
        return $found;
    }

    private function convertBindingToText(Text $binding)
    {
        $value = $binding->getDecoratedValue();
        if ($binding->getColor() === Color::NONE) {
            return $value;
        } else {
            return '<' . $binding->getColor() . '>' . $value . '</>';
        }
    }

    private function buildLines(int $tabs)
    {
        $line = 0;
        $position = $tabs;

        return function (string $word, $i, Output $text, $carry) use ($tabs, &$line, &$position) {
            $isKey = $this->isWordKey($word);
            $binding = $isKey ? $text->getBinding($word) : null;
            if ($isKey) {
                $word = $binding->getDecoratedValue();
            }

            if ($position + strlen($word) > $this->width) {
                $line += 1;
                $carry[$line] = '';
                $position = $tabs;
            } else {
                $position += strlen($word . ' ');
            }

            $carry[$line] .= ($isKey ? $this->convertBindingToText($binding) : $word) . ' ';

            return $carry;
        };
    }

    private function writeLines(Output $text, int $tabs)
    {
        $lines = reduce_left($text, $this->buildLines($tabs), []);
        each($lines, function ($line) use ($tabs, $text) {
            $color = $text->getMessage()->getColor();
            if ($color !== Color::NONE) {
                $line = '<' . $color . '>' . $line . '</>';
            }

            $this->io->write('- ');
            $this->io->writeln(str_repeat(' ', $tabs) . $line);
        });
    }

    function result(Output $title, Output $result, int $depth)
    {
        $this->title($title, $depth);
        if ($result->getStatus() !== 'SUCCESS') {
            $this->writeLines($result, $depth * self::INDENTATION + self::INDENTATION);
        }
    }

    function title(Output $title, int $depth)
    {
        $this->writeLines($title, $depth * self::INDENTATION);
    }
}
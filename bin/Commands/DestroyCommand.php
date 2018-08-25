<?php
/**
 * Created by PhpStorm.
 * User: drupsys
 * Date: 25/08/18
 * Time: 10:42
 */

namespace OneSpec\Cli\Commands;

use DirectoryIterator;
use function Functional\concat;
use function Functional\map;
use function Functional\each;
use function Functional\filter;
use function Functional\intersperse;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DestroyCommand extends Command
{
    /**
     * @var OutputInterface
     */
    private $output;

    protected function configure()
    {
        $this->setName('destroy')
            ->setDescription('Destroys a test')
            ->setHelp('This command will create a test file...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
    }
}
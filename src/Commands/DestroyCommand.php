<?php
/**
 * Created by PhpStorm.
 * User: drupsys
 * Date: 25/08/18
 * Time: 10:42
 */

namespace Xae3Oow5cahz9shahngu\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DestroyCommand extends Command
{
    protected function configure()
    {
        $this->setName('destroy')
            ->setDescription('Destroys a test')
            ->setHelp('This command will create a test file...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<comment>Not implemented...</comment>');
    }
}
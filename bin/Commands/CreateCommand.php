<?php
/**
 * Created by PhpStorm.
 * User: drupsys
 * Date: 25/08/18
 * Time: 10:42
 */

namespace OneSpec\Cli\Commands;

use OneSpec\Cli\Config;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateCommand extends Command
{
    /**
     * @var Config
     */
    private $config;

    public function __construct(string $name = null)
    {
        $this->config = new Config();
        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setName('create')
            ->setDescription('Create a test')
            ->setHelp('This command will create a test file...')
            ->addArgument('class', InputArgument::REQUIRED, 'the class name of the test file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $classArray = explode('/', $input->getArgument('class'));
        $file = array_pop($classArray) . 'Spec.php';

        $path = $this->config->buildSpecPath($classArray);
        if(!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $fullPath = $path . '/' . $file;
        if(file_exists($fullPath)) {
            $output->writeln("<comment>File '<info>" . $fullPath . "</info>' already exists</comment>");
        } else {
            touch($fullPath);
            $output->writeln("<comment>File '<info>" . $fullPath . "</info>' was created successfully</comment>");
        }
    }
}

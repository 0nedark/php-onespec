<?php
/**
 * Created by PhpStorm.
 * User: drupsys
 * Date: 25/08/18
 * Time: 10:42
 */

namespace OneSpec\Cli\Commands;

use DirectoryIterator;
use function Functional\map;
use function Functional\each;
use function Functional\filter;
use OneSpec\Cli\Config;
use OneSpec\Cli\Printer;
use OneSpec\Spec;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RunCommand extends Command
{
    /**
     * @var Config
     */
    private $config;
    private $printer;

    public function __construct(string $name = null)
    {
        $this->config = new Config();
        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setName('run')
            ->setDescription('Runs tests')
            ->setHelp('This command will execute all your spec tests...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->printer = new Printer(new SymfonyStyle($input, $output));
        $this->runTests('./spec');
    }

    private function runTests(string $dir, array $folders = [])
    {
        $directories = map(new DirectoryIterator($dir), $this->toDirectory());
        $validDirectories = filter($directories, $this->validateDirectory());
        each($validDirectories, $this->runTest($folders));
    }

    private function runTest(array $folders): callable {
        return function ($fileInfo) use ($folders) {
            if ($fileInfo->isDirectory) {
                $folders[] = $fileInfo->getFileName;
                $this->runTests($fileInfo->getPathName, $folders);
            } elseif ($fileInfo->isSpec) {
                $folders[] = $fileInfo->getFileName;
                $file = $this->config->buildSpecPath($folders);

                $spec = null; require $file;
                $this->outputTestResults($spec, $file);
            }
        };
    }

    private function toDirectory(): callable {
        return function (DirectoryIterator $fileInfo) {
            return (object)[
                "isSpec" => (bool)preg_match('/.+Spec.php$/', $fileInfo->getFilename()),
                "isValid" => !$fileInfo->isDot(),
                "isDirectory" => $fileInfo->isDir(),
                "getPathName" => $fileInfo->getPathname(),
                "getFileName" => $fileInfo->getFilename(),
            ];
        };
    }

    private function validateDirectory(): callable {
        return function ($directory) {
            return $directory->isValid;
        };
    }

    private function outputTestResults(Spec $spec, string $file)
    {
        $spec->printResults($this->printer, 0, $file);
    }
}
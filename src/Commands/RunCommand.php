<?php
/**
 * Created by PhpStorm.
 * User: drupsys
 * Date: 25/08/18
 * Time: 10:42
 */

namespace Xae3Oow5cahz9shahngu\Commands;

use DirectoryIterator;
use function Functional\map;
use function Functional\each;
use function Functional\filter;
use Xae3Oow5cahz9shahngu\Config;
use Xae3Oow5cahz9shahngu\Printer;
use Xae3Oow5cahz9shahngu\Spec;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RunCommand extends Command
{
    /**
     * @var Config
     */
    private $config;
    /**
     * @var Spec[]
     */
    private $specs = [];
    /**
     * @var SymfonyStyle
     */
    private $io;
    private $printer;
    private $hash;

    public function __construct(string $name = null)
    {
        $this->config = new Config();
        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setName('run')
            ->setDescription('Runs tests')
            ->setHelp('This command will execute all your spec tests...')
            ->addArgument('hash', InputArgument::OPTIONAL, 'The hash of a specific test or block');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->hash = $input->getArgument('hash');
        $this->io = new SymfonyStyle($input, $output);
        $this->printer = new Printer($this->io);
        $this->findTests('./spec');
        $this->runTests();
    }

    private function findTests(string $dir, array $folders = [])
    {
        $directories = map(new DirectoryIterator($dir), $this->toDirectory());
        $validDirectories = filter($directories, $this->validateDirectory());
        each($validDirectories, $this->loadTest($folders));
    }

    private function loadTest(array $folders): callable {
        return function ($fileInfo) use ($folders) {
            if ($fileInfo->isDirectory) {
                $folders[] = $fileInfo->getFileName;
                $this->findTests($fileInfo->getPathName, $folders);
            } elseif ($fileInfo->isSpec) {
                $folders[] = $fileInfo->getFileName;
                $file = $this->config->buildSpecPath($folders);

                $spec = null; require $file;
                $this->specs[] = $spec;
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

    private function runTests()
    {
        foreach ($this->specs as $spec) {
            if (isset($this->hash)) {
                if ($spec->runSpecificTest($this->printer, $this->hash)) {
                    exit(0);
                }
            } else {
                $spec->runSpecInFile($this->printer);
            }
        }
    }
}
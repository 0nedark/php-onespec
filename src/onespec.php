<?php

namespace OneSpec;

require __DIR__ . '/../vendor/autoload.php';

use OneSpec\Commands\CreateCommand;
use OneSpec\Commands\DestroyCommand;
use OneSpec\Commands\RunCommand;
use Symfony\Component\Console\Application;

$application = new Application('OneSpec', '0.0.0');
$application->add(new RunCommand());
$application->add(new CreateCommand());
$application->add(new DestroyCommand());

$application->run();

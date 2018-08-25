#!/usr/bin/env php
<?php

namespace OneSpec\Cli;

require getcwd() . '/vendor/autoload.php';

use OneSpec\Cli\Commands\CreateCommand;
use OneSpec\Cli\Commands\DestroyCommand;
use OneSpec\Cli\Commands\RunCommand;
use Symfony\Component\Console\Application;

$application = new Application('OneSpec', '0.0.0');
$application->add(new RunCommand());
$application->add(new CreateCommand());
$application->add(new DestroyCommand());

$application->run();

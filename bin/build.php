<?php
/**
 * Created by PhpStorm.
 * User: drupsys
 * Date: 28/08/18
 * Time: 21:09
 */

$srcRoot = getcwd();
$buildRoot = getcwd() . "/bin";

if (file_exists($buildRoot . "/onespec")) {
    unlink($buildRoot . "/onespec");
}

$phar = new Phar($buildRoot . "/onespec.phar");
$phar->startBuffering();
$defaultStub = $phar->createDefaultStub('src/onespec.php');
$phar->buildFromDirectory($srcRoot,'/.php$/');
$phar->setStub("#!/usr/bin/env php \n" . $defaultStub);
$phar->stopBuffering();

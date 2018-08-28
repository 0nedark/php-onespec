<?php
/**
 * Created by PhpStorm.
 * User: drupsys
 * Date: 28/08/18
 * Time: 21:09
 */

$srcRoot = getcwd() . "/src";
$buildRoot = getcwd() . "/bin";

if (file_exists($buildRoot . "/onespec")) {
    unlink($buildRoot . "/onespec");
}

$phar = new Phar(
    $buildRoot . "/onespec.phar",
    FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::KEY_AS_FILENAME,
    'onespec'
);

$phar->setDefaultStub('onespec.php');
$phar->buildFromDirectory($srcRoot,'/.php$/');

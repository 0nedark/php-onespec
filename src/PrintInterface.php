<?php
/**
 * Created by PhpStorm.
 * User: drupsys
 * Date: 25/08/18
 * Time: 15:27
 */

namespace OneSpec;

use OneSpec\Result\Result;

interface PrintInterface
{
    function result(string $id, string $name, Result $result, int $depth);
    function title(string $id, string $name, int $depth);
}
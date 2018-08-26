<?php
/**
 * Created by PhpStorm.
 * User: drupsys
 * Date: 25/08/18
 * Time: 15:27
 */

namespace OneSpec;

use OneSpec\Result\Result;
use OneSpec\Result\Title;

interface PrintInterface
{
    function result(Title $title, Result $result, int $depth);
    function title(Title $title, int $depth);
}
<?php
/**
 * Created by PhpStorm.
 * User: drupsys
 * Date: 25/08/18
 * Time: 15:27
 */

namespace OneSpec;

use OneSpec\Result\Icon;
use OneSpec\Result\Output;

interface PrintInterface
{
    function result(Output $title, Icon $icon, Output $result, int $depth);
    function title(Output $title, Icon $icon, int $depth);
}
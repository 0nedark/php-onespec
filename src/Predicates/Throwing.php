<?php
/**
 * Created by PhpStorm.
 * User: drupsys
 * Date: 28/08/18
 * Time: 23:05
 */

namespace OneSpec\Predicates;


use OneSpec\Result\Color;
use OneSpec\Result\Output;
use OneSpec\Result\Text;

trait Throwing
{
    public function throwException(): Output
    {
        $thrown = false;
        try {
            ($this->actual)();
        } catch (\Exception $e) {
            $thrown = true;
        }

        $passed = $this->hasAssertionFailed($thrown);
        $positive = $this->positive ? 'throw' : 'not throw';
        return new Output(
            $this->getStatus($passed),
            new Text("Expected :closure to ${positive} exception", Color::PRIMARY),
            [
                "closure" => new Text('closure', Color::FAILURE)
            ]
        );
    }
}
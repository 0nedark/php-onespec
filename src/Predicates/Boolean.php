<?php
/**
 * Created by PhpStorm.
 * User: drupsys
 * Date: 27/08/18
 * Time: 17:43
 */

namespace Xae3Oow5cahz9shahngu\Predicates;

use Xae3Oow5cahz9shahngu\Result\Color;
use Xae3Oow5cahz9shahngu\Result\Output;
use Xae3Oow5cahz9shahngu\Result\Text;

trait Boolean
{
    public function beTrue(): Output
    {
        $passed = $this->hasAssertionFailed($this->actual === true);
        $positive = $this->positive ? 'be' : 'not be';

        $value = $this->actual;
        if (is_bool($value)) {
            $value = $value ? 'true' : 'false';
        } elseif (is_string($value)) {
            $value = "'$value'";
        } elseif (is_object($value) || is_array($value)) {
            $value = json_encode($value);
        }

        return new Output(
            $this->getStatus($passed),
            new Text("Expected :actual to ${positive} :true", Color::PRIMARY),
            [
                "actual" => new Text($value, Color::FAILURE),
                "true" => new Text('true', Color::SUCCESS),
            ]
        );
    }

    public function beFalse(): Output
    {
        $passed = $this->hasAssertionFailed($this->actual === false);
        $positive = $this->positive ? 'be' : 'not be';

        $value = $this->actual;
        if (is_bool($value)) {
            $value = $value ? 'true' : 'false';
        } elseif (is_string($value)) {
            $value = "'$value'";
        } elseif (is_object($value) || is_array($value)) {
            $value = json_encode($value);
        }

        return new Output(
            $this->getStatus($passed),
            new Text("Expected :actual to ${positive} :false", Color::PRIMARY),
            [
                "actual" => new Text($value, Color::FAILURE),
                "false" => new Text('false', Color::SUCCESS),
            ]
        );
    }
}
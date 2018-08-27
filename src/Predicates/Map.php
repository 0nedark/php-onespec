<?php

namespace OneSpec\Predicates;

use OneSpec\Result\Color;
use OneSpec\Result\Output;
use OneSpec\Result\Text;

trait Map
{
    public function haveKeyValue(array $wanted): Output
    {
        $isStrictModeEnabled = $wanted[2] === true;
        $equal = $isStrictModeEnabled
            ? $this->actual[$wanted[0]] === $wanted[1]
            : $this->actual[$wanted[0]] == $wanted[1];

        $strict = $isStrictModeEnabled ? ' strict ' : ' ';

        $assertion = isset($this->actual[$wanted[0]]) && $equal;
        $passed = $this->hasAssertionFailed($assertion);
        $positive = $this->positive ? 'have' : 'not have';
        $value = $wanted[1];
        if (is_bool($value)) {
            $value = $value ? 'true' : 'false';
        } elseif (is_object($value)) {
            $value = json_encode($value);
        }

        return new Output(
            $this->getStatus($passed),
            new Text("Expected :actual to ${positive} key :key with${strict}value :value", Color::PRIMARY),
            [
                "actual" => new Text(json_encode($this->actual), Color::FAILURE),
                "key" => new Text($wanted[0], Color::SUCCESS),
                "value" => new Text($value, Color::SUCCESS),
            ]
        );
    }
}

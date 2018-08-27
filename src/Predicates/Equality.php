<?php

namespace OneSpec\Predicates;

use OneSpec\Result\Color;
use OneSpec\Result\Output;
use OneSpec\Result\Text;

trait Equality
{
    public function beEqualTo(array $arguments): Output
    {
        $passed = $this->hasAssertionFailed($this->value == $arguments[0]);
        $positive = $this->positive ? 'get' : 'not get';
        return new Output(
            $this->getStatus($passed),
            new Text("Expected to ${positive} :expected but received :actual", Color::PRIMARY),
            [
                "expected" => new Text($arguments[0], Color::SUCCESS),
                "actual" => new Text($this->value, Color::FAILURE),
            ]
        );
    }

    public function beIdenticalTo(array $arguments): Output
    {
        $passed = $this->hasAssertionFailed($this->value === $arguments[0]);
        $positive = $this->positive ? 'be' : 'not be';
        return new Output(
            $this->getStatus($passed),
            new Text("Expected :expected to ${positive} identical to :actual", Color::PRIMARY),
            [
                "expected" => new Text($arguments[0], Color::SUCCESS),
                "actual" => new Text($this->value, Color::FAILURE)
            ]
        );
    }
}

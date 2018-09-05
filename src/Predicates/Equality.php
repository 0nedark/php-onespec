<?php

namespace Xae3Oow5cahz9shahngu\Predicates;

use Xae3Oow5cahz9shahngu\Result\Color;
use Xae3Oow5cahz9shahngu\Result\Output;
use Xae3Oow5cahz9shahngu\Result\Text;

trait Equality
{
    public function beEqualTo(array $wanted): Output
    {
        $passed = $this->hasAssertionFailed($this->actual == $wanted[0]);
        $positive = $this->positive ? 'be' : 'not be';
        return new Output(
            $this->getStatus($passed),
            new Text("Expected :actual to ${positive} like :wanted", Color::PRIMARY),
            [
                "actual" => new Text($this->actual, Color::FAILURE),
                "wanted" => new Text($wanted[0], Color::SUCCESS),
            ]
        );
    }

    public function beIdenticalTo(array $wanted): Output
    {
        $passed = $this->hasAssertionFailed($this->actual === $wanted[0]);
        $positive = $this->positive ? 'be' : 'not be';
        return new Output(
            $this->getStatus($passed),
            new Text("Expected :actual to ${positive} identical to :wanted", Color::PRIMARY),
            [
                "actual" => new Text($this->actual, Color::FAILURE),
                "wanted" => new Text($wanted[0], Color::SUCCESS),
            ]
        );
    }
}

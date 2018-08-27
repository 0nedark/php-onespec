<?php

namespace OneSpec\Predicates;

use OneSpec\Result\Color;
use OneSpec\Result\Output;
use OneSpec\Result\Text;

trait Compare
{
    public function beGreaterThan(array $wanted): Output    {
        $passed = $this->actual > $wanted[0];
        $positive = $this->positive ? 'to' : 'to not';
        return new Output(
            $this->getStatus($passed),
            new Text("Expected :actual ${positive} be greater than :wanted", Color::PRIMARY),
            [
                "actual" => new Text($this->actual, Color::FAILURE),
                "wanted" => new Text($wanted[0], Color::SUCCESS),
            ]
        );
    }

    public function beGreaterThanOrEqualTo(array $wanted): Output
    {
        $passed = $this->actual >= $wanted[0];
        $positive = $this->positive ? 'to' : 'to not';
        return new Output(
            $this->getStatus($passed),
            new Text("Expected :actual ${positive} be greater than or equal to :wanted", Color::PRIMARY),
            [
                "actual" => new Text($this->actual, Color::FAILURE),
                "wanted" => new Text($wanted[0], Color::SUCCESS),
            ]
        );
    }

    public function beLessThan(array $wanted): Output
    {
        $passed = $this->actual < $wanted[0];
        $positive = $this->positive ? 'to' : 'to not';
        return new Output(
            $this->getStatus($passed),
            new Text("Expected :actual ${positive} be less than :wanted", Color::PRIMARY),
            [
                "actual" => new Text($this->actual, Color::FAILURE),
                "wanted" => new Text($wanted[0], Color::SUCCESS),
            ]
        );
    }

    public function beLessThanOrEqualTo(array $wanted): Output
    {
        $passed = $this->actual <= $wanted[0];
        $positive = $this->positive ? 'to' : 'to not';
        return new Output(
            $this->getStatus($passed),
            new Text("Expected :actual ${positive} be less than or equal to :wanted", Color::PRIMARY),
            [
                "actual" => new Text($this->actual, Color::FAILURE),
                "wanted" => new Text($wanted[0], Color::SUCCESS),
            ]
        );
    }

    public function beBetween(array $wanted): Output
    {
        $passed = $this->actual > $wanted[0] && $this->actual < $wanted[1];
        $positive = $this->positive ? 'to' : 'to not';
        return new Output(
            $this->getStatus($passed),
            new Text("Expected :actual ${positive} be exclusively between :left and :right", Color::PRIMARY),
            [
                "actual" => new Text($this->actual, Color::FAILURE),
                "left" => new Text($wanted[0], Color::SUCCESS),
                "right" => new Text($wanted[1], Color::SUCCESS),
            ]
        );
    }

    public function beInclusivelyBetween(array $wanted): Output
    {
        $passed = $this->actual >= $wanted[0] && $this->actual <= $wanted[1];
        $positive = $this->positive ? 'to' : 'to not';
        return new Output(
            $this->getStatus($passed),
            new Text("Expected :actual ${positive} be inclusively between :left and :right", Color::PRIMARY),
            [
                "actual" => new Text($this->actual, Color::FAILURE),
                "left" => new Text($wanted[0], Color::SUCCESS),
                "right" => new Text($wanted[1], Color::SUCCESS),
            ]
        );
    }
}
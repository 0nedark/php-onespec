<?php

namespace OneSpec\Predicates;

use function Functional\contains;
use function Functional\reduce_left;
use function Functional\reject;
use OneSpec\Result\Color;
use OneSpec\Result\Output;
use OneSpec\Result\Text;

trait Map
{
    public function haveKeyValue(array $wanted): Output
    {
        $isStrictModeDisabled = $wanted[2] === true;
        $equal = $isStrictModeDisabled
            ? $this->actual[$wanted[0]] == $wanted[1]
            : $this->actual[$wanted[0]] === $wanted[1];

        $strict = $isStrictModeDisabled ? ' :strict' : '';

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
            new Text("Expected :actual to ${positive} key :key with value :value${strict}", Color::PRIMARY),
            [
                "actual" => new Text(json_encode($this->actual), Color::FAILURE),
                "key" => new Text($wanted[0], Color::SUCCESS),
                "value" => new Text($value, Color::SUCCESS),
                'strict' => new Text('[NOT STRICT]', Color::WARNING),
            ]
        );
    }

    public function containKey(array $wanted): Output
    {
        $isStrictModeDisabled = $wanted[1] === true;
        $strict = $isStrictModeDisabled ? ' :strict' : '';
        $assertion = contains(array_keys($this->actual), $wanted[0], !$isStrictModeDisabled);
        $passed = $this->hasAssertionFailed($assertion);
        $positive = $this->positive ? 'contain' : 'not contain';

        $value = $wanted[0];
        if (is_bool($value)) {
            $value = $value ? 'true' : 'false';
        } elseif (is_object($value)) {
            $value = json_encode($value);
        }

        return new Output(
            $this->getStatus($passed),
            new Text("Expected :actual to ${positive} key :keys${strict}", Color::PRIMARY),
            [
                "actual" => new Text(json_encode($this->actual), Color::FAILURE),
                "keys" => new Text($value, Color::SUCCESS),
                'strict' => new Text('[NOT STRICT]', Color::WARNING),
            ]
        );
    }

    public function containKeys(array $wanted): Output
    {
        $isStrictModeDisabled = $wanted[1] === true;
        $strict = $isStrictModeDisabled ? ' :strict' : '';
        $notFound = reduce_left($this->actual, function($value, $key, $c, $carry) use ($isStrictModeDisabled) {
            return reject($carry, function ($wanted) use ($key, $isStrictModeDisabled) {
                if ($isStrictModeDisabled) {
                    return $wanted == $key;
                } else {
                    return $wanted === $key;
                }
            });
        }, $wanted[0]);

        $passed = $this->hasAssertionFailed(empty($notFound));
        $positive = $this->positive ? 'contain' : 'not contain';
        return new Output(
            $this->getStatus($passed),
            new Text("Expected :actual to ${positive} keys :keys${strict}", Color::PRIMARY),
            [
                "actual" => new Text(json_encode($this->actual), Color::FAILURE),
                "keys" => new Text(json_encode($wanted[0]), Color::SUCCESS),
                'strict' => new Text('[NOT STRICT]', Color::WARNING),
            ]
        );
    }

    public function containOnlyKeys(array $wanted): Output
    {
        $isStrictModeDisabled = $wanted[1] === true;
        $strict = $isStrictModeDisabled ? ' :strict' : '';
        $notFound = reduce_left($wanted[0], function($value, $key, $c, $carry) use ($isStrictModeDisabled) {
            return reject($carry, function ($actualKeys) use ($value, $isStrictModeDisabled) {
                if ($isStrictModeDisabled) {
                    return $actualKeys == $value;
                } else {
                    return $actualKeys === $value;
                }
            });
        }, array_keys($this->actual));

        $passed = $this->hasAssertionFailed(empty($notFound));
        $positive = $this->positive ? 'contain' : 'not contain';
        return new Output(
            $this->getStatus($passed),
            new Text("Expected :actual to ${positive} only keys :keys${strict}", Color::PRIMARY),
            [
                "actual" => new Text(json_encode($this->actual), Color::FAILURE),
                "keys" => new Text(json_encode($wanted[0]), Color::SUCCESS),
                'strict' => new Text('[NOT STRICT]', Color::WARNING),
            ]
        );
    }

    public function containValue(array $wanted): Output
    {
        $isStrictModeDisabled = $wanted[1] === true;
        $strict = $isStrictModeDisabled ? ' :strict' : '';
        $assertion = contains(array_values($this->actual), $wanted[0], !$isStrictModeDisabled);
        $passed = $this->hasAssertionFailed($assertion);
        $positive = $this->positive ? 'contain' : 'not contain';

        $value = $wanted[0];
        if (is_bool($value)) {
            $value = $value ? 'true' : 'false';
        } elseif (is_object($value)) {
            $value = json_encode($value);
        }

        return new Output(
            $this->getStatus($passed),
            new Text("Expected :actual to ${positive} value :value${strict}", Color::PRIMARY),
            [
                "actual" => new Text(json_encode($this->actual), Color::FAILURE),
                "value" => new Text($value, Color::SUCCESS),
                'strict' => new Text('[NOT STRICT]', Color::WARNING),
            ]
        );
    }

    public function containValues(array $wanted): Output
    {
        $isStrictModeDisabled = $wanted[1] === true;
        $strict = $isStrictModeDisabled ? ' :strict' : '';
        $notFound = reduce_left($this->actual, function($value, $key, $c, $carry) use ($isStrictModeDisabled) {
            return reject($carry, function ($wanted) use ($value, $isStrictModeDisabled) {
                if ($isStrictModeDisabled) {
                    return $wanted == $value;
                } else {
                    return $wanted === $value;
                }
            });
        }, $wanted[0]);

        $passed = $this->hasAssertionFailed(empty($notFound));
        $positive = $this->positive ? 'contain' : 'not contain';
        return new Output(
            $this->getStatus($passed),
            new Text("Expected :actual to ${positive} values :values${strict}", Color::PRIMARY),
            [
                "actual" => new Text(json_encode($this->actual), Color::FAILURE),
                "values" => new Text(json_encode($wanted[0]), Color::SUCCESS),
                'strict' => new Text('[NOT STRICT]', Color::WARNING),
            ]
        );
    }

    public function containOnlyValues(array $wanted): Output
    {
        $isStrictModeDisabled = $wanted[1] === true;
        $strict = $isStrictModeDisabled ? ' :strict' : '';
        $notFound = reduce_left($wanted[0], function($value, $key, $c, $carry) use ($isStrictModeDisabled) {
            return reject($carry, function ($actualValues) use ($value, $isStrictModeDisabled) {
                if ($isStrictModeDisabled) {
                    return $actualValues == $value;
                } else {
                    return $actualValues === $value;
                }
            });
        }, array_values($this->actual));

        $passed = $this->hasAssertionFailed(empty($notFound));
        $positive = $this->positive ? 'contain' : 'not contain';
        return new Output(
            $this->getStatus($passed),
            new Text("Expected :actual to ${positive} only values :values${strict}", Color::PRIMARY),
            [
                "actual" => new Text(json_encode($this->actual), Color::FAILURE),
                "values" => new Text(json_encode($wanted[0]), Color::SUCCESS),
                'strict' => new Text('[NOT STRICT]', Color::WARNING),
            ]
        );
    }
}

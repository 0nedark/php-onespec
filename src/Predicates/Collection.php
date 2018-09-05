<?php
/**
 * Created by PhpStorm.
 * User: drupsys
 * Date: 27/08/18
 * Time: 18:12
 */

namespace Xae3Oow5cahz9shahngu\Predicates;

use function Functional\contains;
use function Functional\reduce_left;
use function Functional\reject;
use Xae3Oow5cahz9shahngu\Result\Color;
use Xae3Oow5cahz9shahngu\Result\Output;
use Xae3Oow5cahz9shahngu\Result\Text;

trait Collection
{
    public function beEmpty(): Output
    {
        $passed = $this->hasAssertionFailed(empty($this->actual));
        $positive = $this->positive ? 'be' : 'not be';
        return new Output(
            $this->getStatus($passed),
            new Text("Expected :actual to ${positive} :empty", Color::PRIMARY),
            [
                "actual" => new Text(json_encode($this->actual), Color::FAILURE),
                "empty" => new Text('empty', Color::SUCCESS),
            ]
        );
    }

    public function haveCount(array $wanted): Output
    {
        $passed = $this->hasAssertionFailed(count($this->actual) === $wanted[0]);
        $positive = $this->positive ? 'have' : 'not have';
        return new Output(
            $this->getStatus($passed),
            new Text("Expected :actual to ${positive} :count elements", Color::PRIMARY),
            [
                "actual" => new Text(json_encode($this->actual), Color::FAILURE),
                "count" => new Text($wanted[0], Color::SUCCESS),
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
        $notFoundActual = reduce_left($wanted[0], function($value, $key, $c, $carry) use ($isStrictModeDisabled) {
            return reject($carry, function ($actualValues) use ($value, $isStrictModeDisabled) {
                if ($isStrictModeDisabled) {
                    return $actualValues == $value;
                } else {
                    return $actualValues === $value;
                }
            });
        }, array_values($this->actual));

        $notFoundWanted = reduce_left($this->actual, function($value, $key, $c, $carry) use ($isStrictModeDisabled) {
            return reject($carry, function ($wanted) use ($value, $isStrictModeDisabled) {
                if ($isStrictModeDisabled) {
                    return $wanted == $value;
                } else {
                    return $wanted === $value;
                }
            });
        }, $wanted[0]);

        $passed = $this->hasAssertionFailed(empty($notFoundActual) && empty($notFoundWanted));
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
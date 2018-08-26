<?php

namespace OneSpec;

use OneSpec\Architect\ClassBuilder;

$spec = Spec::class(\DateTime::class);

$spec->before(function (ClassBuilder $obj) {
    $obj->beConstructedWith("now", new \DateTimeZone("UTC"));
});

$spec->describe("asd", function(Spec $spec) {
    $spec->test("a", function ($expect, ClassBuilder $obj) {
        $expect($obj->getTimezone()->getName())->toBeEqualTo("Europe/London");
    });

    $spec->describe("qwe", function(Spec $spec) {
        $spec->test("b", function ($expect, ClassBuilder $obj) {
            $obj->beConstructedWith("now", new \DateTimeZone("Europe/London"));
            $expect($obj->getTimezone()->getName())->toNotBeEqualTo("Europe/London");
        });
    });

    $spec->test("d", function ($expect, ClassBuilder $obj) {
        $obj->setTimezone(new \DateTimeZone('ASD'));
        $expect($obj->getTimezone()->getName())->toBeEqualTo("ASD");
    });
});

$spec->test("c", function ($expect, ClassBuilder $obj) {
    $expect($obj->getTimezone()->getName())->toBeEqualTo("UTC");
});
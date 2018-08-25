<?php

namespace OneSpec;

use OneSpec\Architect\ClassBuilder;

$spec = Spec::class(\DateTime::class);

$spec->before(function (ClassBuilder $obj) {
    $obj->beConstructedWith("now", new \DateTimeZone("UTC"));
});

$spec->describe("asd", function(Spec $spec) {
    $spec->test("", function ($expect, $obj) {
        $expect($obj->getTimezone()->getName())->toBeEqualTo("Europe/London");
    });

    $spec->describe("qwe", function(Spec $spec) {
        $spec->test("", function ($expect, ClassBuilder $obj) {
            $obj->beConstructedWith("now", new \DateTimeZone("Europe/London"));
            $expect($obj->getTimezone()->getName())->toNotBeEqualTo("Europe/London");
        });
    });
});

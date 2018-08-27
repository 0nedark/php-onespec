<?php

namespace OneSpec\Stable;

use OneSpec\Stable\Architect\ClassBuilder;

$spec = Spec::class(__FILE__,\DateTime::class);

$spec->before(function (ClassBuilder $obj) {
    $obj->beConstructedWith("now", new \DateTimeZone("UTC"));
});

$spec->describe("qaz", function(Spec $spec) {
    $spec->it("asd", function ($expect, ClassBuilder $obj) {
        $expect($obj->getTimezone()->getName())->toBeEqualTo("Europe/London");
    });

    $spec->describe("qwe", function(Spec $spec) {
        $spec->it("b", function ($expect, ClassBuilder $obj) {
            $obj->beConstructedWith("now", new \DateTimeZone("Europe/London"));
            $expect(43)->toBeInclusivelyBetween(43,45);
        });
    });

    $spec->it("should have a UTC timezone", function ($expect, ClassBuilder $obj) {
        $expect($obj->getTimezone()->getName())->BeEqualTo("UTC");
    });
});

$spec->it("d", function ($expect, ClassBuilder $obj) {
    $obj->setTimezone(new \DateTimeZone('ASD'));
    $expect($obj->getTimezone()->getName())->toBeEqualTo("ASD");
});

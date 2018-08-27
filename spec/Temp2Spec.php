<?php

namespace OneSpec;

use OneSpec\Architect\ClassBuilder;

$spec = Spec::class(__FILE__,\DateTime::class);

$spec->before(function (ClassBuilder $obj) {
    $obj->beConstructedWith("now", new \DateTimeZone("UTC"));
});

$spec->describe("some A", function(Spec $spec) {
    $spec->it("some B", function ($expect, ClassBuilder $obj) {
        sleep(1);
        $expect($obj->getTimezone()->getName())->toBeEqualTo("Europe/London");
    });

    $spec->describe("some C", function(Spec $spec) {
        $spec->it("some D", function ($expect, ClassBuilder $obj) {
            $obj->beConstructedWith("now", new \DateTimeZone("Europe/London"));
            $expect($obj->getTimezone()->getName())->toNotBeEqualTo("Europe/London");
        });
    });

    $spec->it("some E", function ($expect, ClassBuilder $obj) {
        $expect($obj->getTimezone()->getName())->toBeEqualTo("UTC");
    });
});

$spec->it("some F", function ($expect, ClassBuilder $obj) {
    $obj->setTimezone(new \DateTimeZone('ASD'));
    $expect($obj->getTimezone()->getName())->toBeEqualTo("ASD");
});

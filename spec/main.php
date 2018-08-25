<?php

namespace OneSpec;

$desc = Describe::class(\DateTime::class);

$desc->before(function ($obj) {
    $obj->beConstructedWith("now", new \DateTimeZone("UTC"));
});

$desc->describe("asd", function(Describe $desc) {
    $desc->test("", function ($expect, $obj) {
        $expect($obj->getTimezone()->getName())->toBeEqualTo("Europe/London");
    });

    $desc->describe("qwe", function(Describe $desc) {
        $desc->test("", function ($expect, $obj) {
            $obj->beConstructedWith("now", new \DateTimeZone("Europe/London"));
            $expect($obj->getTimezone()->getName())->toNotBeEqualTo("Europe/London");
        });
    });
});

return $desc;
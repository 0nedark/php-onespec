<?php

namespace OneSpec;

require '/app/vendor/autoload.php';

$desc = Describe::class(\DateTime::class);

$desc->before(function ($obj) {
    $obj->beConstructedWith("now", new \DateTimeZone("UTC"));
});

$desc->group("asd", function(Describe $desc) {
    $desc->test("", function ($expect, $obj) {
        $expect($obj->getTimezone()->getName())->toBeEqualTo("Europe/London");
    });

    $desc->group("qwe", function(Describe $desc) {
        $desc->test("", function ($expect, $obj) {
            $obj->beConstructedWith("now", new \DateTimeZone("Europe/London"));
            $expect($obj->getTimezone()->getName())->toNotBeEqualTo("Europe/London");
        });
    });
});

print_r($desc->getOutput());
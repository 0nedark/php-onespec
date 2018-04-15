<?php

namespace OneSpec;

require '/app/vendor/autoload.php';

$desc = Describe::class(\DateTime::class);

$desc->setBefore(function ($obj) {
    $obj->beConstructedWith("now", new \DateTimeZone("Europe/London"));
});

$desc->test("", function ($expect, $obj) {
    $expect($obj->getTimezone()->getName())->toBeEqualTo("Europe/London");
});
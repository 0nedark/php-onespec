<?php

namespace spec;

use OneSpec\Architect\ClassBuilder;
use OneSpec\Spec;
use Xae3Oow5cahz9shahngu\Spec as Mock;

$spec = Spec::class(__FILE__,Mock::class);

$spec->before(function (ClassBuilder $obj) {
    $obj->beConstructedWith('hash', 'name', new ClassBuilder(\DateTimeZone::class));
});

$spec->describe("name hashing", function(Spec $spec) {
    $spec->it("should not throw exception if test name is in a different describe", function ($expect, ClassBuilder $obj) {
        $expect(function () use ($obj) {
            $obj->describe('name hashing', function (Spec $spec) {
                $spec->describe('name hashing', function (Spec $spec) {});
            });
        })->toNotThrowException();
    });
});

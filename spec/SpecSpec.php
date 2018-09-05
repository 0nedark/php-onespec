<?php

namespace spec;

use OneSpec\Architect\ClassBuilder;
use OneSpec\Spec;
use Xae3Oow5cahz9shahngu\Spec as Mock;

$spec = Spec::class(__FILE__,Mock::class);

$spec->describe("name hashing", function(Spec $spec) {
    $spec->it("should not throw exception if test name is in a different describe", function ($expect, ClassBuilder $obj) {
        $expect(function () {
            $obj = Mock::class('file', \DateTimeZone::class);
            $obj->describe('name hashing', function ($spec) {
                $spec->describe('name hashing', function ($spec) {});
            });
        })->toNotThrowException();
    });
});
